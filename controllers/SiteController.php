<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Photo;
use app\models\User;

class SiteController extends Controller
{
    public function home()
    {
        $photos = (new Photo())->findAll();

        $photosData = [];
        foreach ($photos as $photo) {
            $user = (new User())->findOne(['id' => $photo->user_id]);
            $photosData[] = [
                'id' => $photo->id,
                'path' => $photo->path,
                'name' => $photo->name,
                'user' => [
                    'firstname' => $user->firstname,
                ],
                'addCheckbox' => false
            ];
        }

        $photosJson = json_encode($photosData);

        return $this->render('home', ['photosJson' => $photosJson]);
    }
}
