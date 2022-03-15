<?php

namespace app\controllers;

use app\models\Commentaries;
use app\models\User;
use app\models\Video;
use Yii;
use yii\console\ExitCode;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * VideoController implements the CRUD actions for Video model.
 */
class VideoController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Video models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }
        $query = Video::find();
        if (!Yii::$app->user->identity->isAdmin)
            $query->where(['UserId' => Yii::$app->user->id])->andWhere('Status != 4');
        else if ($this->request->isPost) {
            $video = Video::findOne(['Id' => $_POST['Video']['Id']]);
            $video->Status = $_POST['Video']['Status'];
            $video->save(false);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            */
            'sort' => [
                // 'defaultOrder' => [
                //     'id' => SORT_DESC,
                // ],
                'attributes' => [
                    'Title',
                    'CreatedAt'
                ]
            ],

        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Video model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->Status > 2)
            $this->err404();
        $comment = new Commentaries();
        if ($this->request->isPost && $comment->load($this->request->post())) {
            if ($comment->validate()) {
                $comment->save();
                return $this->redirect(['view', 'id' => $id]);
            }
        }
        $comment->loadDefaultValues();
        return $this->render('view', [
            'model' => $model,
            'commentaries' => $comment,
        ]);
    }

    /**
     * Creates a new Video model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Video();

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->upload() && $model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Video model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->user->id !== $model->UserId)
            $this->err404();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Video model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Video model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Video the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Video::findOne($id)) !== null) {
            return $model;
        }

        $this->err404();
    }

    public function err404()
    {
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
