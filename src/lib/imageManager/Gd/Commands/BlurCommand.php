<?php

namespace YiiMan\LibUploadManager\lib\imageManager\Gd\Commands;

class BlurCommand extends \YiiMan\LibUploadManager\lib\imageManager\Commands\AbstractCommand
{
    /**
     * Applies blur effect on image
     *
     * @param  \YiiMan\LibUploadManager\lib\imageManager\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $amount = $this->argument(0)->between(0, 100)->value(1);

        for ($i=0; $i < intval($amount); $i++) {
            imagefilter($image->getCore(), IMG_FILTER_GAUSSIAN_BLUR);
        }

        return true;
    }
}
