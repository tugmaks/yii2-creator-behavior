Yii2 creator behavior
=====================
Creator behavior for Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist tugmaks/yii2-creator-behavior "*"
```

or add

```
"tugmaks/yii2-creator-behavior": "*"
```

to the require section of your `composer.json` file.


Usage
-----

  CreatorBehavior automatically fills the specified attributes with the current user id.
 
  To use CreatorBehavior, insert the following code to your ActiveRecord class:

```php
  use tugmaks\behaviors\CreatorBehavior;
 
  public function behaviors()
  {
      return [
          CreatorBehavior::className(),
      ];
  }
```
 
  By default, CreatorBehavior will fill the `created_by` and `updated_by` attributes with the current user id
  when the associated AR object is being inserted; it will fill the `updated_by` attribute
  with the current user id when the AR object is being updated. The user id value is obtained by `Yii::$app->user->id`.
 
  For the above implementation to work with MySQL database, please declare the columns(`created_by`, `updated_by`) as int(11).
 
  If your attribute names are different or you want to use a different way of retrieving user id,
  you may configure the [[createdByAttribute]], [[updatedByAttribute]] and [[value]] properties like the following:
 
```php
  public function behaviors()
  {
      return [
          [
              'class' => CreatorBehavior::className(),
              'createdByAttribute' => 'creator_id',
              'updatedByAttribute' => 'updater_id',
              'value'=>function($event){
                   return \Yii::$app->user->identity->getCustomId();
               }
          ],
      ];
  }
```
