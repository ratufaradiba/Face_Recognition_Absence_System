</div>

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Sistem Informasi Presensi Siswa 2023</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin ingin keluar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Silahkan klik logout untuk melanjutkan</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batalkan</button>
                <a class="btn btn-info" href="<?php echo base_url(); ?>dashboard/logout">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo base_url(); ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo base_url(); ?>assets/js/sb-admin-2.min.js"></script>

<script src="<?php echo base_url(); ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/demo/datatables-demo.js"></script>

<!-- Page level plugins -->
<script src="<?php echo base_url(); ?>assets/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="<?php echo base_url(); ?>assets/js/demo/chart-area-demo.js"></script>
<!-- <script src="<?php echo base_url(); ?>assets/js/demo/chart-pie-demo.js"><  /script> -->

<script>
    function changeRole() {
        const role = $("#role").val()
        if (role == 'admin') {
            $('.rolePegawai').hide()
        } else {
            $('.rolePegawai').show()
        }
    }
</script>

<script>
    try {
        var ctx = document.getElementById("myAreaChart2");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["K-1", "K-2", "K-3", "K-4", "K-5"],
                datasets: [{
                    label: "Persentase",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: <?php echo json_encode($kvold_acc); ?>,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return '' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error with myAreaChart2:', error);
    }
</script>

<script>
    try {
        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Positif (%)", "Negatif (%)", "Netral (%)"],
                datasets: [{
                    data: [<?php echo (int) 100 - (int) $negatif_perc - (int) $netral_perc; ?>, <?php echo (int) $negatif_perc; ?>, <?php echo (int) $netral_perc; ?>],
                    backgroundColor: ['#1cc88a', '#ff5252', '#36b9cc'],
                    hoverBackgroundColor: ['#17a673', '#ff0000', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    } catch (error) {
        console.error('Error with myPieChart:', error);
    }
</script>

<script>
    try {
        // Pie Chart Example
        var ctx2 = document.getElementById("myPieChart2");
        var myPieChart2 = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ["Sesuai (%)", "Tidak Sesuai (%)"],
                datasets: [{
                    data: [<?php echo $_hasil_acc; ?>, <?php echo (int) 100 - $_hasil_acc; ?>],
                    backgroundColor: ['#1cc88a', '#ff5252'],
                    hoverBackgroundColor: ['#17a673', '#ff0000'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    } catch (error) {
        console.error('Error with myPieChart2:', error);
    }
</script>

<script>
    try {
        // Pie Chart Example
        var ctx = document.getElementById("myPieChart3");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Positif (%)", "Negatif (%)", "Netral (%)"],
                datasets: [{
                    data: [<?php echo (int) 100 - (int) $negatif_perc - (int) $netral_perc; ?>, <?php echo (int) $negatif_perc; ?>, <?php echo (int) $netral_perc; ?>],
                    backgroundColor: ['#1cc88a', '#ff5252', '#36b9cc'],
                    hoverBackgroundColor: ['#17a673', '#ff0000', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    } catch (error) {
        console.error('Error with myPieChart3:', error);
    }
</script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script>
    try {
        $('#datatable2').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                text: 'Download Excel',
                extend: 'excel',
                title: null,
                exportOptions: {
                    columns: ':visible:not(.exclude-export)'
                }
            }, ],
        });
        $('.buttons-excel').hide();
        $('#exportData').on('click', function() {
            $('.buttons-excel').click();
        });
    } catch (error) {
        console.error('Error with DataTable:', error);
    }
</script>

</body>

</html>