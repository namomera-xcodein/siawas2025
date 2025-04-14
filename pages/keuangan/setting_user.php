<?php
// include '../../config/koneksi.php';
// include '../templates/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $nik_nip = $_POST['nik_nip'];
        $jabatan = $_POST['jabatan'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $level_user = $_POST['level_user'];

        $query = "INSERT INTO users (name, nik_nip, jabatan, email, password, role, level_user) 
                  VALUES ('$name', '$nik_nip', '$jabatan', '$email', '$password', '$role', '$level_user')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'User added successfully',
                        icon: 'success'
                    });
                  </script>";
        }
    }

    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $nik_nip = $_POST['nik_nip'];
        $jabatan = $_POST['jabatan'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $level_user = $_POST['level_user'];

        $query = "UPDATE users SET 
                  name='$name', 
                  nik_nip='$nik_nip', 
                  jabatan='$jabatan', 
                  email='$email', 
                  role='$role',
                  level_user='$level_user'
                  WHERE id=$id";
        
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $query = "UPDATE users SET 
                      name='$name', 
                      nik_nip='$nik_nip', 
                      jabatan='$jabatan', 
                      email='$email', 
                      password='$password', 
                      role='$role',
                      level_user='$level_user'
                      WHERE id=$id";
        }

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'User updated successfully',
                        icon: 'success'
                    });
                  </script>";
        }
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $query = "DELETE FROM users WHERE id=$id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'User deleted successfully',
                        icon: 'success'
                    });
                  </script>";
        }
    }
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">User Management</h1>

    <!-- Add User Button -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addUserModal">
        Add New User
    </button>

    <!-- DataTables -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>NIK/NIP</th>
                            <th>Jabatan</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Level</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT u.*, l.name as level_name 
                                 FROM users u 
                                 LEFT JOIN level l ON u.level_user = l.id";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>".$row['id']."</td>";
                            echo "<td>".$row['name']."</td>";
                            echo "<td>".$row['nik_nip']."</td>";
                            echo "<td>".$row['jabatan']."</td>";
                            echo "<td>".$row['email']."</td>";
                            echo "<td>".$row['role']."</td>";
                            echo "<td>".$row['level_name']."</td>";
                            echo "<td>
                                    <button class='btn btn-sm btn-warning' onclick='editUser(".$row['id'].")'>Edit</button>
                                    <button class='btn btn-sm btn-danger' onclick='deleteUser(".$row['id'].")'>Delete</button>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>NIK/NIP</label>
                        <input type="text" class="form-control" name="nik_nip" required>
                    </div>
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Level User</label>
                        <select class="form-control" name="level_user" required>
                            <?php
                            $query = "SELECT * FROM level";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='".$row['id']."'>".$row['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="add" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                    <div class="form-group">
                        <label>NIK/NIP</label>
                        <input type="text" class="form-control" name="nik_nip" id="edit_nik_nip" required>
                    </div>
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" id="edit_jabatan" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="edit_email" required>
                    </div>
                    <div class="form-group">
                        <label>Password (Leave blank to keep current password)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role" id="edit_role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Level User</label>
                        <select class="form-control" name="level_user" id="edit_level_user" required>
                            <?php
                            $query = "SELECT * FROM level";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='".$row['id']."'>".$row['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="edit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});

function editUser(id) {
    $.ajax({
        url: 'get_user.php',
        type: 'POST',
        data: {
            id: id
        },
        success: function(response) {
            var user = JSON.parse(response);
            $('#edit_id').val(user.id);
            $('#edit_name').val(user.name);
            $('#edit_nik_nip').val(user.nik_nip);
            $('#edit_jabatan').val(user.jabatan);
            $('#edit_email').val(user.email);
            $('#edit_role').val(user.role);
            $('#edit_level_user').val(user.level_user);
            $('#editUserModal').modal('show');
        }
    });
}

function deleteUser(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var form = $('<form action="" method="post">' +
                '<input type="hidden" name="id" value="' + id + '">' +
                '<input type="hidden" name="delete" value="1">' +
                '</form>');
            $('body').append(form);
            form.submit();
        }
    });
}
</script>

<?php include '../templates/footer.php'; ?>