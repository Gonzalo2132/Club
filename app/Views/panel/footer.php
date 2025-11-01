
           <!-- Footer -->
           <footer class="sticky-footer bg-white">
               <div class="container my-auto">
                   <div class="copyright text-center my-auto">
                       <span>Copyright &copy; Instituto Superior "Gaspar L. Benavento" Tec. Análisis y Desarrollo de Software 2025</span>
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
   <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
       aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                   <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">×</span>
                   </button>
               </div>
               <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
               <div class="modal-footer">
                   <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                   <a class="btn btn-primary" href="login.html">Logout</a>
               </div>
           </div>
       </div>
   </div>

   <!-- Bootstrap core JavaScript-->
   <script src="<?php echo base_url(); ?>public/js/vendor/jquery/jquery.min.js"></script>
   <script src="<?php echo base_url(); ?>public/js/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

   <!-- Core plugin JavaScript-->
   <script src="<?php echo base_url(); ?>public/js/vendor/jquery-easing/jquery.easing.min.js"></script>

   <!-- Custom scripts for all pages-->
   <script src="<?php echo base_url(); ?>public/js/sb-admin-2.min.js"></script>

   <!-- Page level plugins -->
   <script src="<?php echo base_url(); ?>public/js/vendor/chart.js/Chart.min.js"></script>

   <!-- Page level custom scripts -->
   <script src="<?php echo base_url(); ?>public/js/demo/chart-area-demo.js"></script>
   <script src="<?php echo base_url(); ?>public/js/demo/chart-pie-demo.js"></script>


<!-- DataTables con botones -->
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
  new DataTable('#contenido-lista', {
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
    },
    layout: {
      topStart: {
        buttons: [
          {
            extend: 'csv',
            text: '<i class="fas fa-file-csv"></i> CSV',
            className: 'btn btn-success btn-sm'
          },
          {
            extend: 'excel',
            text: '<i class="fas fa-file-excel"></i> Excel',
            className: 'btn btn-primary btn-sm'
          },
          {
            extend: 'pdf',
            text: '<i class="fas fa-file-pdf"></i> PDF',
            className: 'btn btn-danger btn-sm'
          },
          {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Imprimir',
            className: 'btn btn-info btn-sm'
          }
        ]
      }
    }
  });
</script>
   </body>

   </html>