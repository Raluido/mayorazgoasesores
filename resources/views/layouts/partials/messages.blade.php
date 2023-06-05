@if (Session::get('errors'))
<?php $data = Session::get('errors')->toJson(JSON_UNESCAPED_UNICODE); ?>
<div class="red messages" role="">
    <i class="fa fa-check"></i>
    {{ substr($data, 3, (strlen($data) - 6)) }}
</div>
@elseif (Session::get('success'))
<?php $data = Session::get('success'); ?>
<div class="green messages" role="">
    <i class="fa fa-check"></i>
    {{ $data }}
</div>
@endif