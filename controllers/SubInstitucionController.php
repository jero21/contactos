<?php

namespace app\controllers;

use Yii;
use app\models\SubInstitucion;
use app\models\Institucion;
use app\models\Direccion;
use app\models\SubInstitucionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * SubInstitucionController implements the CRUD actions for SubInstitucion model.
 */
class SubInstitucionController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all SubInstitucion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubInstitucionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubInstitucion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SubInstitucion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SubInstitucion();
        $dir = new Direccion();

        $institucion = ArrayHelper::map(Institucion::find()->all(),'id_institucion','nombre');

        if ($model->load(Yii::$app->request->post()) && $dir->load(Yii::$app->request->post())) {

            $model->save();
            $dir->Institucionid_institucion = $model->id_sub_institucion;

            if($dir->save()){
                return $this->redirect(['view', 'id' => $model->id_sub_institucion]);
            }else{
                return $this->redirect(['index', 'id' => $model->id_sub_institucion]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'institucion' => $institucion,
                'dir' => $dir,
            ]);
        }
    }

    /**
     * Updates an existing SubInstitucion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $dir = Direccion::find()->where(['id_direccion'=>$model->id_sub_institucion])->one();
        $institucion = ArrayHelper::map(Institucion::find()->all(),'id_institucion','nombre');
        
        if ($model->load(Yii::$app->request->post()) && $dir->load(Yii::$app->request->post())) {

            $model->save();
            if($dir->save()){
                return $this->redirect(['view', 'id' => $model->id_institucion]);
            }else{
                return $this->redirect(['index', 'id' => $model->id_institucion]);
            }
        } else {
            return $this->render('update', [
                'institucion' => $institucion,
                'model' => $model,
                'dir' => $dir,
            ]);
        }
    }

    /**
     * Deletes an existing SubInstitucion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SubInstitucion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubInstitucion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubInstitucion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
