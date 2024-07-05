<?php
/** @var $model app\models\User */
/** @var $this app\core\View */

use app\core\form\Form;

$this->title='Login';
?>

<h1>Login</h1>
<?php $form = Form::begin('', 'post') ?>
<?php echo $form->field($model, 'email') ?>
<?php echo $form->field($model, 'password')->passwordField() ?>

<button type="submit" class="btn">Submit</button>
<?php Form::end() ?>
