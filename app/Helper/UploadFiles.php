
<?php

    function uploadImg($request , $path)
    {
        if($request->hasFile('file')){

            $file = $request->file;
            // Lấy tên file
            $file->getClientOriginalName();
            // Lấy đuôi file
            $file->getClientOriginalExtension();
            // Lấy kích thước file
            $file->getSize();
            $file->move($path,$file->getClientOriginalName());
            $thumbnail = $file->getClientOriginalName();
            return $thumbnail;
        }

    }
