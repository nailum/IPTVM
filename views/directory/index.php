<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\base\Widget;
use app\models\Timezone;
$this->title = 'Directory List';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
	<div class="btn-group">
    	<?= Html::a('Delete Selected', [''], ['class' => 'btn btn-danger delete-all', 'disabled' => 'disabled']) ?>
    	<span class="btn btn-default delete-num">0</span>
    </div>
    <?= Html::a('New Directory', ['create'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Import Directories', ['import'], ['class' => 'btn btn-warning']) ?>
    <?= Html::a('Export Directories', ['export'], ['class' => 'btn btn-info']) ?>
</p>
<?= GridView::widget([
    'options' => ['class' => 'gridview', 'style' => 'overflow:auto', 'id' => 'grid'],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pager' => [
        'firstPageLabel' => 'First Page',
        'lastPageLabel' => 'Last Page',
    ],
    'rowOptions' => function($model, $key, $index, $grid){
        return ['class' => $index % 2 == 0 ? 'label-white' : 'label-grey' ];
    },
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'name' => 'id',
            'checkboxOptions' => function ($model, $key, $index, $column){
                if(!empty($model->childrenDirectories)){
                    return ['disabled' => 'disabled'];
                }
                return [];
            },
            'headerOptions' => ['width' => '10'],
        ],
        [
            'class' => 'yii\grid\SerialColumn',
            'headerOptions' => ['width' => '10'],
        ],
        'directoryName',
        [
            'attribute' => 'parentName',
            'format' => 'raw',
            'value' => function($model){
                if(!empty($model->parentDirectory)){
                    return Html::a($model->parentDirectory->directoryName, ['directory/view', 'directoryId' => $model->parentId], ['title' => 'view']);
                }
            }
        ],
        'showOrder',
        [
            'attribute' => 'createTime',
            'value' => function($model){
                return Timezone::date($model->createTime);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Operations',
            'headerOptions' => ['width' => '90'],
            'template' => '{view}&nbsp;&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;{delete}',
            'buttons' => [
                'view' => function($url, $model, $key){
                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',
                    ['view', 'directoryId' => $key],
                    ['title' => 'View']);
                },
                'update' => function($url, $model, $key){
                return Html::a('<i class="glyphicon glyphicon-pencil"></i>',
                    ['update', 'directoryId' => $key],
                    ['title' => 'Update']);
                },
                'delete' => function($url, $model, $key){
                    if(!empty($model->childrenDirectories)){
                        return '<i class="glyphicon glyphicon-trash" style="color:gray;"></i>';
                    }
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                    ['delete', 'directoryId' => $key],
                    ['title' => 'Delete',
                     'data' => ['confirm' => "Are you sure to delete directory $model->directoryName?"],
                    ]);
                },
            ],
        ],
    ],
]); 
$this->registerJs("
$(document).on('click', '.gridview', function () {
    var keys = $('#grid').yiiGridView('getSelectedRows');
    if(keys.length>0){
        $('.delete-all').attr('disabled', false);
        $('.delete-num').html(keys.length);
        $('.delete-all').attr('href', 'index.php?r=directory/delete-all&keys='+keys);
    }else{
        $('.delete-all').attr('disabled', 'disabled');
        $('.delete-num').html(0);
        $('.delete-all').attr('href', '');
    }
});
$(document).on('click', '.delete-all', function(){
    if($(this).attr('disabled')){
        return false;
    }else{
        var num = $('.delete-num').html();
        if(!confirm('are you sure to delete these '+num+' directories?')){
            return false;
        }
    }
});
");
?>
<p>
	<div class="btn-group">
    	<?= Html::a('Delete Selected', [''], ['class' => 'btn btn-danger delete-all', 'disabled' => 'disabled']) ?>
    	<span class="btn btn-default delete-num">0</span>
    </div>
    <?= Html::a('New Directory', ['create'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Import Directories', ['import'], ['class' => 'btn btn-warning']) ?>
    <?= Html::a('Export Directories', ['export'], ['class' => 'btn btn-info']) ?>
</p>
