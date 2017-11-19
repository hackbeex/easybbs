<?php

namespace App\Tools;

use Image;

class ImageUploadTool
{
    protected $allowedExt = ["png", "jpg", "gif", 'jpeg', 'bmp'];

    /**
     * save upload file
     * @param $file \Illuminate\Http\UploadedFile
     */
    public function save($file, $folder, $filePrefix, $maxWidth = false)
    {
        // path rule
        $folderName = "uploads/images/$folder/" . date("Ym", time()) . '/'.date("d", time()).'/';

        $uploadPath = public_path() . '/' . $folderName;

        // some img hasn't extension, set default is png
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        $filename = $filePrefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        if ( ! in_array($extension, $this->allowedExt)) {
            return false;
        }

        $file->move($uploadPath, $filename);

        if ($maxWidth && $extension != 'gif') {
            $this->reduceSize($uploadPath . '/' . $filename, $maxWidth);
        }

        return [
            'path' => config('app.url') . "/$folderName/$filename"
        ];
    }

    public function reduceSize($filePath, $maxWidth)
    {
        $image = Image::make($filePath);

        $image->resize($maxWidth, null, function ($constraint) {

            // 设定宽度是 $maxWidth，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        $image->save();
    }
}