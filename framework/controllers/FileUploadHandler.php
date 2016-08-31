<?php
/**
*	FileUploadController code.
*
*	PHP version 5.3
*
*	@category Controller
*	@package FileUpload
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class FileUploadHandler {

	public function __construct()
    {
		$this->types = array();
		$this->fileInfo = array();
        $this->getTypes();
    }


    public function getTypes()
    {

    	foreach ($_FILES as $name => $file) {
    		
            $fileType = explode( "/", $file['type'] );
            $type = "upload_" . $fileType[0];
    		array_push($this->types, $type);
    		$this->fileInfo[ $name ] = $fileType;

    	}
    	return $this->types;
    
    }

    public function getFileType( $index )
    {
        return $this->fileInfo[ $index ][0];
    }

    public function getFilenameExtension( $index )
    {
    	return $this->fileInfo[ $index ][1];
    }

    public function generateRandomString($length = 20)
    {
    	$str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    	return substr(str_shuffle($str), 0, $length);
	}

    public function getFileName( $key )
    {
        return $this->generateRandomString() . "." . $this->getFilenameExtension( $key );
    }

    public function uploading(){    	

    	$result = array();
        if( empty($_FILES) )
        {
            return false;
        }
        // var_dump($_FILES);
        foreach ($_FILES as $key => $file) {

            $file_name = $this->getFileName($key);
            $uploadUrl = UPLOAD . $this->getFileType($key) . "/" . $file_name;
            $uploadResult = move_uploaded_file( $file["tmp_name"], $uploadUrl );
            
            if( $uploadResult )
            {
                $result[ $key ] = array( 
                        "isSuccess"=>"true", 
                        "fileName" => $file_name,
                        "type" => $file['type'],
                        "size" => $file["size"]
                    );
            }
            else
            {
                $result[ $key ] = array( "isSuccess"=>"false" );
            }
        }
        return $result;
    }


}



?>
