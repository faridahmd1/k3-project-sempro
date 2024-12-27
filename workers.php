<?php
include 'config.php';

// Set up pagination
$limit = 10; // Number of records per page
$page = isset($_GET['page_number']) ? (int)$_GET['page_number'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Fetch total number of records
$total_result = $conn->query("SELECT COUNT(*) AS total FROM workers WHERE deleted_at IS NULL");
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch workers with limit and offset
$sql = "SELECT * FROM workers WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <h1>Workers</h1>
      <a href="index.php?page=add_worker" class="btn btn-primary mb-3">Add Worker</a>
      <table id="workers_table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Job Type</th>
            <th>NIK</th>
            <th>Phone</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['gender'] ?></td>
            <td><?= $row['jenis_pekerjaan'] ?></td>
            <td><?= $row['nik'] ?></td>
            <td><?= $row['phone_num'] ?></td>
            <td>
              <a href="index.php?page=edit_worker&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="index.php?page=delete_worker&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <nav>
        <ul class="pagination justify-content-center">
          <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="index.php?page=workers&page_number=<?= $page - 1 ?>" tabindex="-1">Previous</a>
          </li>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
            <a class="page-link" href="index.php?page=workers&page_number=<?= $i ?>"><?= $i ?></a>
          </li>
          <?php endfor; ?>
          <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
            <a class="page-link" href="index.php?page=workers&page_number=<?= $page + 1 ?>">Next</a>
          </li>
        </ul>
      </nav>
    </div>
  </section>
</div>

<script>
  $(document).ready(function () {
    $('#workers_table').DataTable({
      paging: false, // Disable DataTables pagination (use PHP pagination instead)
      info: false,   // Disable info
      searching: true // Disable search (optional)
    });
  });
</script>
