<?php $view->extend('layout.php') ?>

<?php $view['slots']->set('title', 'Interesant') ?>

<h1>
    <?php echo 'Interesant' ?>
</h1>
<p>
    <?php echo $view->render($table['template'], ['data' => $form]) ?>
</p>
    <script src="/js/itav/main.js" type="text/javascript"></script>
    <script src="/js/itav/test.js" type="text/javascript"></script>
    <script src="/js/itav/offer.js" type="text/javascript"></script>

