<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ProtonBlobChecker - Blob Errors</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.addons.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../vendors/iconfonts/font-awesome/css/font-awesome.min.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png"/>
</head>

<body>
<div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
            <a class="navbar-brand brand-logo" href="../../index.php">
                ProtonBlobChecker
            </a>
            <a class="navbar-brand brand-logo-mini" href="../../index.php">
                ProtonBlobChecker
            </a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item nav-profile">
                    <div class="nav-link">
                        <div class="user-wrapper">
                            <div class="text-wrapper">
                                <p class="profile-name">Matteo Mara</p>
                                <div>
                                    <small class="designation text-muted">Software Developer</small>
                                    <span class="status-indicator online"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../index.php">
                        <i class="menu-icon mdi mdi-television"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../pages/blob/blobErrors.php">
                        <i class="menu-icon mdi mdi-table"></i>
                        <span class="menu-title">Blob Errors</span>
                    </a>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#messages" aria-expanded="false" aria-controls="messages">
                        <i class="menu-icon mdi mdi-email-alert"></i>
                        <span class="menu-title">Message Errors</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="messages">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="../../pages/messages/messageErrors.php">
                                    <span class="menu-title">Message Data</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../pages/messages/sentMessageErrors.php">
                                    <span class="menu-title">Sent Messages</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#attachments" aria-expanded="false" aria-controls="attachments">
                        <i class="menu-icon mdi mdi-paperclip"></i>
                        <span class="menu-title">Attachment Errors</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="attachments">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="../../pages/attachments/attachmentErrors.php">
                                    <span class="menu-title">Attachments</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../pages/attachments/sentAttachmentErrors.php">
                                    <span class="menu-title">Sent Attachments</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../pages/attachments/outsideAttachmentErrors.php">
                                    <span class="menu-title">Outside Attachments</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../pages/contact/contactDataErrors.php">
                        <i class="menu-icon mdi mdi-account-plus"></i>
                        <span class="menu-title">Contact Data</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Sent Messages with missing blob references</h4>
                                <p class="card-description">
                                    Items discovered in the DB referencing Blobs not available anymore
                                </p>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Entity ID</th>
                                            <th>Entity Type</th>
                                            <th>Blob ID</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        include '../../sentMessageCheck.php';

                                        $result = getSentMessageErrors();

                                        if ($result->num_rows > 0) {
                                            // output data of each row
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr><td>" . $row["EntityId"] . "</td><td>" . $row["EntityDesc"] . "</td><td>" . $row["MissingBlobId"] . "</td></tr>";
                                            }
                                            echo "</table>";
                                        } else {
                                            echo "0 results";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="../../vendors/js/vendor.bundle.base.js"></script>
<script src="../../vendors/js/vendor.bundle.addons.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="../../js/off-canvas.js"></script>
<script src="../../js/misc.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<!-- End custom js for this page-->
</body>

</html>