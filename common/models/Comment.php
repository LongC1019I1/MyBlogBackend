<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $body
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $post_id
 * @property int|null $created_by
 *
 * @property User $createdBy
 * @property Post $post
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['body'], 'string'],
            [['created_at', 'updated_at', 'post_id', 'created_by'], 'integer'],
            [['title'], 'string', 'max' => 512],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    public function fields()
    {
        return ['id', 'title', 'body'];
    }

    public function extraFields()
    {
        return ['post'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'body' => 'Body',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'post_id' => 'Post ID',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PostQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CommentQuery(get_called_class());
    }
}
