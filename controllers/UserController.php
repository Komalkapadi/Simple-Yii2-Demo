<?php

namespace app\controllers;

use app\models\Hobbies;
use app\models\UserHobby;
use app\models\Users;
use app\models\UsersSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for Users model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $userData = $this->findModel($id);
        // Set Gender's value
        if (!empty($userData)) {
            $userData['gender'] = ($userData['gender'] == 1 ? 'Male' : 'Female');
        }

        return $this->render('view', [
            'model' => $userData,
        ]);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $userHobby = new UserHobby();

        $hobbies = ArrayHelper::map(Hobbies::find()->all(), 'id', 'hobby');
        if ($model->load(Yii::$app->request->post())) {
            $password = md5($model->password);
            $model->password = $password;
            $model->save();
            $postData = Yii::$app->request->post();
            $saveHobby = array();
            if (!empty($postData['UserHobby']['hobby_id'])) {
                $lstInsertId = Yii::$app->db->getLastInsertID();
                foreach ($postData['UserHobby']['hobby_id'] as $key => $value) {
                    $saveHobby[$key][] = $lstInsertId;
                    $saveHobby[$key][] = $value;
                }
                Yii::$app->db->createCommand()->batchInsert('user_hobby', ['user_id', 'hobby_id'],
                    array_values($saveHobby))->execute();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'userHobby' => $userHobby,
            'userOldHobbies' => '',
            'hobby' => $hobbies,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userOldHobbies = UserHobby::find()->where(['user_id' => $id])->asArray()->all();
        $userOldHobbies = ArrayHelper::map($userOldHobbies, 'id', 'hobby_id');
        $userOldHobbies = ($userOldHobbies);
        $userHobby = new UserHobby();
        $hobbies = ArrayHelper::map(Hobbies::find()->all(), 'id', 'hobby');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $postData = Yii::$app->request->post();
            $saveHobby = array();
            if (!empty($postData['UserHobby']['hobby_id'])) {
                // Deletes existing hobbies of that user
                Yii::$app
                    ->db
                    ->createCommand()
                    ->delete('user_hobby', ['user_id' => $id])
                    ->execute();

                foreach ($postData['UserHobby']['hobby_id'] as $key => $value) {
                    $saveHobby[$key][] = $id;
                    $saveHobby[$key][] = $value;
                }
                Yii::$app->db->createCommand()->batchInsert('user_hobby', ['user_id', 'hobby_id'],
                    array_values($saveHobby))->execute();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'userHobby' => $userHobby,
            'userOldHobbies' => $userOldHobbies,
            'hobby' => $hobbies,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

}
