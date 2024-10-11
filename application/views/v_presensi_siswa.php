<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Halaman Presensi Siswa</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url(); ?>assets/css/sb-admin-2.css" rel="stylesheet">
    <style>
        .bg-login-image {
            background: url("<?php echo base_url(); ?>assets/img/login.png");
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-primary" style="
        height: auto;
        margin: auto;
        transform: translateY(-50%);
        top: 50%;
        position: absolute;
        width: 100%;
    ">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <img src="<?php echo base_url(); ?>assets/img/klasifikasi.png" style="height: 250px; width: auto;" />
                                        <h1 class="h4 text-gray-900 mt-3">Sistem Informasi Presensi Siswa <?php echo $kelas['nama_kelas'];?> </h1> 
                                        <p>Mohon untuk mengaktifkan kamera dan setelah klik<br />presensi sekarang mohon untuk diam dalam beberapa detik</p>
                                        <!-- <hr /> -->
                                    </div>
                                    <!-- Display the video feed from the camera -->
                                    <video id="video" width="800" height="300" autoplay></video>

                                    <!-- Button to capture the image -->
                                    <button id="capture" class="mt-3 btn btn-md btn-primary w-100" style="background: #283342; border: 1px solid #283342;">Presensi Sekarang</button>
                                    <button id="loading" class="mt-3 btn btn-md btn-success w-100" style="display: none;">Mohon Menunggu...</button>

                                    <!-- Display the captured image -->
                                    <img id="capturedImage" width="800" style="display: none;">

                                    <script>
                                        // Get user media (camera) and display it in the video element
                                        navigator.mediaDevices.getUserMedia({
                                                video: true
                                            })
                                            .then(function(stream) {
                                                var video = document.getElementById('video');
                                                video.srcObject = stream;
                                            })
                                            .catch(function(err) {
                                                console.error('Error accessing the camera: ', err);
                                            });

                                        document.getElementById('capture').addEventListener('click', function() {
                                            var captureButton = document.getElementById('capture');
                                            var loadingButton = document.getElementById('loading');

                                            captureButton.style.display = 'none';
                                            loadingButton.style.display = 'block';

                                            var video = document.getElementById('video');
                                            var canvas = document.createElement('canvas');
                                            var context = canvas.getContext('2d');

                                            // Set canvas dimensions to match the video feed
                                            canvas.width = video.videoWidth;
                                            canvas.height = video.videoHeight;

                                            // Draw the current frame from the video onto the canvas
                                            context.drawImage(video, 0, 0, canvas.width, canvas.height);

                                            // Convert the canvas content to a data URL representing the image
                                            var imageDataURL = canvas.toDataURL('image/png');

                                            // Display the captured image
                                            // var capturedImage = document.getElementById('capturedImage');
                                            // capturedImage.src = imageDataURL;
                                            // capturedImage.style.display = 'block';

                                            // Send the image data to PHP for saving
                                            var xhr = new XMLHttpRequest();
                                            xhr.open('POST', '<?php echo base_url(); ?>siswa/lakukan_presensi', true);
                                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                                            // Set up the callback for a successful API call
                                            xhr.onload = function() {
                                                if (xhr.status === 200) {
                                                    // Show a success alert
                                                    var response = JSON.parse(xhr.responseText);
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Berhasil',
                                                        text: response.message,
                                                        confirmButtonText: 'OK'
                                                    });
                                                    // .then(function() {
                                                    //     location.reload();
                                                    // });
                                                    captureButton.style.display = 'block';
                                                    loadingButton.style.display = 'none';
                                                }else if (xhr.status === 300) {
                                                    // Show a success alert
                                                    var response = JSON.parse(xhr.responseText);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error',
                                                        text: response.message,
                                                        confirmButtonText: 'OK'
                                                    });
                                                    // .then(function() {
                                                    //     location.reload();
                                                    // });
                                                    captureButton.style.display = 'block';
                                                    loadingButton.style.display = 'none';
                                                }else if (xhr.status === 400) {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Terlambat',
                                                        text: 'Maaf kamu tidak dapat Presensi karena sudah terlambat',
                                                        confirmButtonText: 'OK'
                                                    });
                                                    captureButton.style.display = 'block';
                                                    loadingButton.style.display = 'none';
                                                } else if (xhr.status === 500) {
                                                    // Show an alert for server error
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error',
                                                        text: 'Maaf kamu tidak dapat Presensi karena data tidak ditemukan',
                                                        confirmButtonText: 'OK'
                                                    });
                                                    captureButton.style.display = 'block';
                                                    loadingButton.style.display = 'none';
                                                } else {
                                                    // Handle other status codes or errors here
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error',
                                                        text: 'Unexpected error. Status code:' + xhr.status,
                                                        confirmButtonText: 'OK'
                                                    });
                                                    console.error('Unexpected error. Status code:', xhr.status);
                                                    captureButton.style.display = 'block';
                                                    loadingButton.style.display = 'none';
                                                }
                                            };

                                            // Send the image data as POST data
                                            xhr.send('imageData=' + encodeURIComponent(imageDataURL));
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>