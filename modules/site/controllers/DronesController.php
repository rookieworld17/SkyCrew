<?php

namespace modules\site\controllers;

use Craft;
use craft\web\Controller;
use craft\elements\Entry;
use yii\web\Response;

class DronesController extends Controller
{
    protected array|bool|int $allowAnonymous = true;

    public function actionSearch(): Response
    {
        $query = Craft::$app->request->getRequiredParam('q');

        $drones = Entry::find()
            ->section('drones')
            ->search('*' . $query . '*')
            ->orderBy('RAND()')
            ->limit(10)
            ->all();

        $result = [];

        foreach ($drones as $drone) {
            $result[] = [
                'title' => $drone->title,
                'id'  => $drone->id,
            ];
        }

        return $this->asJson($result);
    }
}
