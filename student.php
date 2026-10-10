<?php
include "config.php";
// Only logged-in students can see this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') { header("Location: index.php"); exit; }
$uid = $_SESSION['user_id'];
$msg = "";

// Apply for a job
if (isset($_GET['apply'])) {
    $job_id = (int)$_GET['apply'];
    $cgpa = $conn->query("SELECT cgpa FROM users WHERE id=$uid")->fetch_assoc()['cgpa'];
    $min = $conn->query("SELECT min_cgpa FROM jobs WHERE id=$job_id")->fetch_assoc()['min_cgpa'];
    if ($cgpa < $min) {
        $msg = "Your CGPA is below the required $min";
    } else {
        $stmt = $conn->prepare("INSERT INTO applications (user_id, job_id) VALUES (?,?)");
        $stmt->bind_param("ii", $uid, $job_id);
        $msg = $stmt->execute() ? "Applied successfully!" : "You already applied.";
    }
}

$jobs = $conn->query("SELECT * FROM jobs");
$apps = $conn->query("SELECT jobs.company, jobs.title, applications.status
                      FROM applications JOIN jobs ON applications.job_id = jobs.id
                      WHERE applications.user_id = $uid");
include "header.php";
?>
<p class="error"><?= $msg ?></p>

<h2>Available Jobs</h2>
<table>
  <tr><th>Company</th><th>Role</th><th>Package</th><th>Min CGPA</th><th></th></tr>
  <?php while ($j = $jobs->fetch_assoc()) { ?>
  <tr>
    <td><?= htmlspecialchars($j['company']) ?></td>
    <td><?= htmlspecialchars($j['title']) ?></td>
    <td><?= htmlspecialchars($j['package']) ?></td>
    <td><?= $j['min_cgpa'] ?></td>
    <td><a class="btn" href="student.php?apply=<?= $j['id'] ?>">Apply</a></td>
  </tr>
  <?php } ?>
</table>

<h2>My Applications</h2>
<table>
  <tr><th>Company</th><th>Role</th><th>Status</th></tr>
  <?php while ($a = $apps->fetch_assoc()) { ?>
  <tr>
    <td><?= htmlspecialchars($a['company']) ?></td>
    <td><?= htmlspecialchars($a['title']) ?></td>
    <td><?= $a['status'] ?></td>
  </tr>
  <?php } ?>
</table>
<?php include "footer.php"; ?>
