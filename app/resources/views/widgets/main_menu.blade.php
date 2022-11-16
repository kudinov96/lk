@foreach($items as $item)		
	<li class="{{ $config['menu_class'] }}"><a href="{{ $item->link() }}">{{ $item->title }}</a></li>
@endforeach