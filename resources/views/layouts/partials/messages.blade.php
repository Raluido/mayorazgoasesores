@if (Session::get('errors'))
<?php $data = Session::get('errors'); ?>
<div class="red messages" role="">
    <i class="fa fa-check"></i>
    {{ $data }}
</div>
@elseif (Session::get('success'))
<?php $data = Session::get('success'); ?>
<div class="green messages" role="">
    <i class="fa fa-check"></i>
    {{ $data }}
</div>
@endif