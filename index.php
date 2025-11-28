<?php
// ===================================================================
// 1. LOGIKA PHP YANG SUDAH DIPERBAIKI (HARUS DI ATAS TAG HTML)
// (Bagian ini tidak diubah, hanya memastikan logika tetap aman dan benar)
// ===================================================================

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Koneksi Database
$koneksi = mysqli_connect('localhost', 'root', '', 'bootcamp_todolist2025');

if (mysqli_connect_errno()) {
    die("Gagal terhubung ke MySQL: " . mysqli_connect_error());
}

// --- TAMBAH TASK (CREATE) - Menggunakan Prepared Statement ---
if (isset($_POST['add_task'])) {
    $task = $_POST['task'];
    $priority = $_POST['priority']; 
    $due_date = $_POST['due_date'];

    if (!empty($task) && !empty($priority) && !empty($due_date)) {
        
        $query = "INSERT INTO tasks (task, priority, due_date, status) VALUES (?, ?, ?, 0)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "sis", $task, $priority, $due_date);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = 'Data Berhasil Disimpan!';
        } else {
            $_SESSION['pesan_gagal'] = 'Gagal menyimpan data: ' . mysqli_error($koneksi);
        }
        
        mysqli_stmt_close($stmt);
        header('location:index.php');
        exit();
        
    } else {
        $_SESSION['pesan_gagal'] = 'Semua Kolom Harus Diisi!!';
        header('location:index.php');
        exit();
    }
}

// --- MENANDAI TASK SELESAI (UPDATE) ---
if (isset($_GET['complete'])) {
    $id = $_GET['complete'];
    
    if (filter_var($id, FILTER_VALIDATE_INT)) {
        
        $query = "UPDATE tasks SET status=1 WHERE id=?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = 'Data Berhasil Diperbarui!';
        } else {
             $_SESSION['pesan_gagal'] = 'Gagal memperbarui status: ' . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt);
        
    } else {
        $_SESSION['pesan_gagal'] = 'ID tidak valid!';
    }
    
    header('location:index.php');
    exit();
}

// --- MENGHAPUS TASKS (DELETE) ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    if (filter_var($id, FILTER_VALIDATE_INT)) {
        
        $query = "DELETE FROM tasks WHERE id=?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = 'Data Berhasil Dihapus!';
        } else {
            $_SESSION['pesan_gagal'] = 'Gagal menghapus data: ' . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt);

    } else {
        $_SESSION['pesan_gagal'] = 'ID tidak valid!';
    }
    
    header('location:index.php');
    exit();
}

// --- AMBIL DATA (READ) ---
$result = mysqli_query($koneksi, "SELECT * FROM tasks ORDER BY status ASC, priority DESC, due_date ASC");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi to do list sederhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        .task-done {
            text-decoration: line-through;
            color: gray;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Aplikasi toDolist</h2>

        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['pesan_sukses']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['pesan_sukses']); ?>
        <?php elseif (isset($_SESSION['pesan_gagal'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['pesan_gagal']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['pesan_gagal']); ?>
        <?php endif; ?>
        
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8"> 
                <form action="" method="POST" class="border rounded bg-light p-4 shadow-sm mb-4">
                    <label for="task" class="form-label">Nama Task</label>
                    <input type="text" name="task" class="form-control mb-2" placeholder="masukkan task baru" autocomplete="off" autofocus required>
                    
                    <label for="priority" class="form-label">Prioritas</label>
                    <select name="priority" id="priority" class="form-control mb-2" required> 
                        <option value="">--Pilih Prioritas--</option>
                        <option value="1">Low</option>
                        <option value="2">Medium</option>
                        <option value="3">High</option>
                    </select>
                    
                    <label for="due_date" class="form-label">Tanggal</label>
                    <input type="date" name="due_date" class="form-control mb-3" value="<?php echo date('Y-m-d'); ?>" required>
                    
                    <button type="submit" class="btn btn-primary w-100" name="add_task">Tambah</button>
                </form>
            </div>
        </div>
        <hr>

        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Task</th>
                    <th>Priority</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(mysqli_num_rows($result) > 0){
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result)) { 
                    
                    $row_class = $row['status'] == 1 ? 'task-done table-success' : '';
                    
                    $priority_badge_class = 'bg-secondary';
                    if ($row['priority'] == 1) {
                        $priority_badge_class = 'bg-success';
                    } elseif ($row['priority'] == 2) {
                        $priority_badge_class = 'bg-warning text-dark';
                    } elseif ($row['priority'] == 3) {
                        $priority_badge_class = 'bg-danger';
                    }
                    ?>
                    <tr class="<?php echo $row_class; ?>">
                        <td><?php echo $no++; ?></td>
                        <td>
                            <?php 
                            echo $row['status'] == 1 ? '<del>' . htmlspecialchars($row['task']) . '</del>' : htmlspecialchars($row['task']); 
                            ?>
                        </td>
                        <td>
                            <span class="badge <?php echo $priority_badge_class; ?>">
                            <?php
                            if($row['priority'] == 1){ echo "Low"; }
                            else if($row['priority'] == 2){ echo "Medium"; }
                            else{ echo "High"; }
                            ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                        <td>
                            <?php
                            if($row['status'] == 0){ echo"Belum Selesai !!"; }
                            else{ echo"Selesai"; }
                            ?>
                        </td>
                        <td>
                            <?php if($row['status'] == 0){ ?>
                            <a href="index.php?complete=<?php echo $row['id']?>" class="btn btn-success btn-sm" onclick="return confirm('Tandai task ini sebagai selesai?');">Selesai</a>
                            <?php }?>
                            <a href="index.php?delete=<?php echo $row['id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus task ini?');">Hapus</a>
                        </td>
                    </tr>
                        <?php
                    }
                }else{
                    echo"<tr><td colspan='6' class='text-center'>Tidak Ada Data!!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>