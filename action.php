<?php 


//action.php 

//this function will return  size of individual file 
function format_folder_size($size)
{
    if($size >= 1073741824)
    {
        $size = number_format($size / 1073741824,2).'GB';
    }
    elseif($size >= 1048576){
          $size = number_format($size / 1048576,2).'MB';
    }
    elseif($size >= 1024)
    {
        $size = number_format($size / 1048576,2).'KB';
    }
    elseif($size > 1){
         $size = $size."bytes";
    }
    else{
        $size = "0 bytes";
    }
    return $size;
}


///This will return total size of the given folder
function get_folder_size($folder_name)
{
   $total_size = 0;
   $file_data  = scandir($folder_name);
   foreach($file_data as $file)
   {
       if($file === '.' || $file === '..')
       {
          continue;
       }
       else{
           $path       = $folder_name.'/'.$file;
           $total_size = $total_size +filesize($path);
       }
   }
   return format_folder_size($total_size);
}



// echo array_filter(glob('*'),'is_dir');
if(isset($_POST['action']))
{
    if($_POST['action'] == 'fetch')
    {
         $folder = array_filter(glob('*'),'is_dir');
         $output = '
         <table class="table table-hover table-bordered">
            <tr>
                <th>Folder Name</th>
                <th>Total File</th>
                <th>Size</th>
                <th>Update</th>
                <th>Delete</th>
                <th>Upload File</th>
                <th>View Uploaded File</th>
            </tr>';
         
          if(count($folder) > 0 )
          {
              foreach($folder as $name)
              {
                $output .='
                <tr> 
                    <td>'.$name.'</td>
                    <td>'.(count(scandir($name))-2).'</td>
                    <td>'.get_folder_size($name).'</td>
                    <td>
                        <button type="button" name="update" data-name="'.$name.'" class="update btn btn-warning btn-xs" >Update</button>
                    </td>
                    <td>
                        <button type="button" name="delete" data-name="'.$name.'" class="delete btn btn-danger btn-xs" >
                        Delete</button>
                    </td>
                    <td>
                        <button type="button" name="upload" data-name="'.$name.'" class="upload btn btn-info btn-xs">
                        Upload File 
                        </button>
                    </td>
                    <td>
                        <button type="button" name="view_files" data-name="'.$name.'" class="view_files btn btn-default btn-xs">
                            View Files
                        </button>
                    </td>
                </tr>
                ';
              }
          }
          else{
              $output .= '
                <tr>
                    <td colspan="6">No Folder Found</td>
                </tr>';
          }
          $output  .= "</table>";

          echo $output;
    }

    if($_POST['action'] == 'create')
    {
        if(!file_exists($_POST['folder_name']))
        {
           mkdir($_POST['folder_name']);
           echo "Folder Created";
        }
        else{
            echo "Folder Already Created";
        }
    }

    if($_POST['action'] == 'change')
    {
         if(!file_exists($_POST['folder_name']))
         {
           rename($_POST['old_name'],$_POST['folder_name']);
           echo "Folder Name Change";
         }
         else{
               echo "Folder Already Created";
         }
         
    }

    if($_POST['action'] == 'fetch_files')
    {
        $file_data  = scandir($_POST['folder_name']);

        $output     = '
           <table class="table table-bordered table-striped">
              <tr> 
                 <th>Image</th>
                 <th>File Name</th>
                 <th>Delete</th>
              </tr>';

        foreach($file_data as $file)
        {
            if($file  === '.' || $file === '..' )
            {
                 continue;
            }
            else{
               
                 $path    = $_POST['folder_name'].'/'.$file;
                 $output .='
                    <tr>
                       <td>
                         <img src="'.$path.'" class="img-thumbnail" height="50" width="50" />
                       </td>
                       <td contenteditable="true" data-folder_name="'.$_POST['folder_name'].'" data-file_name="'.$file.'" class="change_file_name">'.$file.' </td>
                       <td>
                          <button name="remove_file" class="remove_file btn btn-danger btns-xs" id="'.$path.'">
                            Remove
                          </button>
                       </td>
                    </tr>';
            }
        }      

        $output .= "</table>";
        echo $output;
    }

    if($_POST['action'] == 'remove_file')
    {
        if(file_exists($_POST['path']))
        {
            if(!is_dir($_POST['path']))
            {
                    unlink($_POST['path']);
                    echo "File Deleted";
            }
            else{
                    rmdir($_POST['path']);
                    echo "Folder is deleted";
            }   
        }
    }

    if($_POST['action'] == 'delete')
    {
        if(file_exist($_POST['folder_name']))
        {
              $files = scandir($_POST['folder_name']);
              foreach($files as $file)
              {

                if($file === '.' || $file === '..')
                {
                    continue;
                }
                 
                //if this is directory then remove it 
                if(is_dir($file))
                { 
                    rmdir($_POST['folder_name'].'/'.$file);
                }

                // if this is file then remove it 
                else{
                     
                    unlink($_POST['folder_name'].'/'.$file);
                }
              
              }
              if(rmdir($_POST['folder_name']))
              {
                  echo "Folder Deleted";
              }

             
        }
         
    }

    if($_POST['action'] == 'change_file_name')
    {
        $old_name = $_POST['folder_name'].'/'.$_POST['old_file_name'];
        $new_name = $_POST['folder_name'].'/'.$_POST['new_file_name'];

        if(rename($old_name,$new_name))
        {
            echo "File name change successfully";
        }
        else{
            echo "There is an error";
        }

    }
}
?>
