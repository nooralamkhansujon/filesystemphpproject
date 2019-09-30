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

<div id="folderModal" class="modal fade" role="dialog">
    <div class="model-dialog">
        <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <h4 class="modal-title">
                 <span id="change_title">Create Folder</span>
             </h4>
          </div>
          <div class="modal-body">
            <p>Enter Folder Name
            <input type="text" name="folder_name" id="folder_name"></p>
            <br />
             <input type="hidden" name="action" id="action" />
             <input type="hidden" name="old_name" id="old_name" />
             <input type="button" name="folder_button" id="folder_button" class="btn btn-info" value="Create" />
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
  });
</script>