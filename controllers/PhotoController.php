<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\IsGuestMiddleware;
use app\core\Request;
use app\models\Photo;
use app\models\User;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new IsGuestMiddleware(['upload', 'myPhotos']));
    }

    public function upload(Request $request)
    {
        $this->setLayout('main');

        if ($request->isPost()) {
            $photo = new Photo();
            $userId = Application::$app->session->get('user');
            $targetDir = "uploads/$userId/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $originalFilename = basename($_FILES["photo"]["name"]);
            $targetFile = $targetDir . $originalFilename;
            $photo->loadData($request->getBody());
            $photo->user_id = $userId;
            $photo->path = $targetFile;
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                if ($photo->validate() && $photo->save()) {
                    Application::$app->session->setFlash('success', 'Photo saved.');
                    Application::$app->response->redirect('/');
                }
            }
        }
        return $this->render('upload');
    }

    public function myPhotos()
    {
        $userId = Application::$app->session->get('user');
        $photos = (new Photo())->findAllAuthUser(['user_id' => $userId]);

        $photosData = [];
        $header = 'My photos';
        foreach ($photos as $photo) {
            $user = (new User())->findOne(['id' => $photo->user_id]);
            $photosData[] = [
                'id' => $photo->id,
                'path' => $photo->path,
                'name' => $photo->name,
                'user' => [
                    'firstname' => $user->firstname,
                ],
                'addCheckbox' => true,
            ];
        }

        $photosJson = json_encode($photosData);

        return $this->render('home', ['photosJson' => $photosJson, 'header' => $header]);
    }

    public function delete(Request $request)
    {
        $body = $request->getJsonBody();

        $requestData = json_decode($body, true);

        if (isset($requestData['ids'])) {
            $photoIds = $requestData['ids'];

            foreach ($photoIds as $photoId) {
                $photo = (new Photo())->findOne(['id' => $photoId]);
                if ($photo) {
                    $photo->delete();
                }
            }

            return json_encode(['success' => true]);
        } else {
            return json_encode(['error' => 'Invalid request format']);
        }
    }

    public function photoDetail(Request $request)
    {

        $photoId = $request->getBody()['id'] ?? null;
        if ($photoId) {
            $photo = (new Photo())->findOne(['id' => $photoId]);
            if ($photo) {
                $user = (new User())->findOne(['id' => $photo->user_id]);
                return $this->render('photo-details', [
                    'photo' => $photo,
                    'user' => $user,
                ]);
            }
        }

        Application::$app->response->setStatusCode(404);
        return $this->render('404');
    }

    public function wishlist()
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

        return $this->render('wishlist', ['photosJson' => $photosJson]);
    }

}
