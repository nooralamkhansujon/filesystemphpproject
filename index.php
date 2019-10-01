<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
   <br /> <br />
   <div class="container">
       <h2 align="center">List Folder from Directory - PHP Filesystem  with Ajax JQuery -1</h2>
       <br />

        <div align="right">
           <button type="button" name="create_folder" id="create_folder" class="btn btn-success">
           Create</button>
        </div>
        <div id="folder_table" class="table-responsive">
             
        </div>
   </div>
    
</body>
</html>

<div id="folderModal" class="modal fade"  role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <h4 class="modal-title">
                 <span id="change_title">Create Folder</span>
             </h4>
          </div>
          <div class="modal-body">
            <p>Enter Folder Name
            <input type="text" class="form-control" name="folder_name" id="folder_name"></p>
            <br />
             <input type="hidden" name="action" id="action" />
             <input type="hidden" name="old_name" id="old_name" />
             <input type="button" name="folder_button" id="folder_button" class="btn btn-info" value="Create" />
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
</div>



<div id="uploadModal" class="modal fade"  role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <h4 class="modal-title">
                 <span id="change_title">Upload Folder</span>
             </h4>
          </div>
          <div class="modal-body">
              <form  id="upload_form" enctype="multipart/form-data">
                  <p>Select Image
                     <input type="file" name="upload_file" />
                  </p>
                  <br /> 
                  <input type="hidden" name="hidden_folder_name" id="hidden_folder_name" />
                  <input type="submit" name="upload_button" class="btn btn-info" value="Upload" />
              </form>
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
</div>



<div id="filelistModal" class="modal fade"  role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <h4 class="modal-title">
                 <span id="change_title">File List</span>
             </h4>
          </div>
          <div class="modal-body" id="file_list">
                 
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
</div>



<script>
  $(document).ready(function(){
        
       load_folder_list();
       function load_folder_list(){
           
             let action = 'fetch';
             $.ajax({
                 url    : "action.php",
                 method : "POST",
                 data   : {action:action},
                 success: function(data){
                     
                     $("#folder_table").html(data);
                 }
             })
       }
      
      // This will create new folder 
      $(document).on('click','#create_folder',function(){
          $("#action").val('create');
          $("#folder_name").val('');
          $("#folder_button").val('Create');
          $('#old_name').text('Create Folder');
          $("#folderModal").modal('show');
      });
     
     // This is folder button click
      $(document).on('click','#folder_button',function(){
          let folder_name = $("#folder_name").val();
          let action      = $("#action").val();
          let old_name    = $("#old_name").val();
          if(folder_name != '')
          {
               $.ajax({
                   url   :"action.php",
                   method:"POST",
                   data  :{folder_name:folder_name,old_name:old_name,action:action},
                   success:function(data){
                       $("#folderModal").modal('hide');
                       load_folder_list();
                       alert(data);
                   }
               })
          }
          else{
              alert("Enter Folder Name");
          }
      });
     
     // this is update folder name 
     $(document).on('click','.update',function(){
            let folder_name = $(this).data('name');
            $("#old_name").val(folder_name);
            $("#folder_name").val(folder_name);
            $("#action").val("change");
            $("#folder_button").val('Update');
            $("#change_title").text("Change Folder Name");
            $("#folderModal").modal('show');
     });

     $(document).on('click','.upload',function(){

         let folder_name = $(this).data('name');
         $("#hidden_folder_name").val(folder_name);
         $("#uploadModal").modal('show');

     });
     $("#upload_form").on('submit',function(){
          $.ajax({
              url     : "upload.php",
              method  : "POST",
              data    : new FormData(this),
              contentType : false,
              cache       : false,
              processData : false,
              success     : function(data)
              {
                load_folder_list();
                alert(data);
              }

          });
     });

      $(document).on('click','.view_files',function(){
            
            let folder_name = $(this).data("name");
            // console.log(folder_name);
            let action = "fetch_files";
            $.ajax({
                url    : "action.php",
                method : "POST",
                data   : {action:action,folder_name:folder_name},
                success:function(data)
                {
                    console.log(data);
                    $("#file_list").html(data);
                    $("#filelistModal").modal('show');
                }

            })
      });

      $(document).on('click','.remove_file',function(){
            let path = $(this).attr('id');
            let action = "remove_file";
            if(confirm("Are You sure you want to remove this File?"))
            {
                  $.ajax({
                      url:"action.php",
                      method:"POST",
                      data:{path:path,action:action},
                      success:function(data)
                      {
                          alert(data);
                          $("#filelistModal").modal('hide');
                          load_folder_list();
                      }
                  })
            }
            else{
                return false;
            }
      });

  });
</script>