<?php 
require_once('head_html.php'); 
require_once('../Includes/config.php'); 
require_once('../Includes/session.php'); 
require_once('../Includes/admin.php'); 

if (!$logged) {
    header("Location:user/index.php");
    exit();
}
?>
<body>
    <div id="wrapper">
        <?php 
            require_once("nav.php");
            require_once("sidebar.php");
        ?>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Complaints</h1>
                        <ol class="breadcrumb">
                          <li>Complaint</li>
                          <li class="active">Not Processed</li>
                        </ol>
                    </div><!-- /.col-lg-12 -->
                </div><!-- /.row -->
                
                <div class="table-responsive" style="padding-top: 0">
                    <table class="table table-hover table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Complaint No.</th>
                                <th>User</th>
                                <th>Complaint</th>
                                <th>Status</th>
                                <th>Process</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $id = $_SESSION['aid'];
                                $query1 = "SELECT COUNT(complaint.id) 
                                           FROM user, complaint 
                                           WHERE complaint.uid=user.id 
                                           AND status='NOT PROCESSED' 
                                           AND complaint.aid=?";
                                $stmt1 = mysqli_prepare($con, $query1);
                                mysqli_stmt_bind_param($stmt1, 'i', $id);
                                mysqli_stmt_execute($stmt1);
                                mysqli_stmt_bind_result($stmt1, $numrows);
                                mysqli_stmt_fetch($stmt1);
                                mysqli_stmt_close($stmt1);

                                include("paging1.php");
                                $result = retrieve_complaints_history($id, $offset, $rowsperpage);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<tr>';
                                    echo '<td>CA-' . htmlspecialchars($row['id']) . '</td>';
                                    echo '<td height="50">' . htmlspecialchars($row['uname']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['complaint']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                                    echo '<td width="70">
                                            <form action="process_complaint.php" method="post">
                                                <input type="hidden" name="cid" value="' . htmlspecialchars($row['id']) . '">
                                                <button type="submit" name="complaint_process" class="btn btn-success form-control">PROCESS COMPLAINT</button>
                                            </form>
                                          </td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php include("paging2.php"); ?>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <?php 
        require_once("footer.php");
        require_once("js.php");
    ?>
</body>
</html>
