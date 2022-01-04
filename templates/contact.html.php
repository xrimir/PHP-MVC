<?php if ($session->has('success')): ?>
    <p><?php echo htmlentities($session->getFlash('success')) ?></p>
<?php endif ?>

<?php if (!empty($errors)): ?>
<p>Niestety w przesłanych danych wystąpiły błędy.</p>
<?php var_dump($errors) ?>
<?php endif ?>

<form method="post">
    <?php echo $form->render() ?>
    <button type="submit">
        Send!
    </button>
</form>
