<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$column['lat']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="row">
            <div class="col-sm-3 {{$hidden_lng_lat_input ? 'hidden' : ''}}">
                <input id="{{$column['lng']}}" name="{{$name['lng']}}" class="form-control"
                       value="{{ old($column['lng'], @$value['lng']) }}" {!! $attributes !!} />
            </div>
            <div class="col-sm-3 {{$hidden_lng_lat_input ? 'hidden' : ''}}">
                <input id="{{$column['lat']}}" name="{{$name['lat']}}" class="form-control"
                       value="{{ old($column['lat'], @$value['lat']) }}" {!! $attributes !!} />
            </div>

            <div class="{{$hidden_lng_lat_input ? 'col-sm-12' : 'col-sm-6'}}">
                <div class="input-group">
                    <input type="text" class="form-control" id="{{$column['address']}}" name="{{$column['address']}}" value="{{@$value['address']}}">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div>

        </div>

        <br>

        <div id="map_{{$id['lat'].$id['lng']}}" style="width: 100%;height: {{ $height }}px"></div>

        @include('admin::form.help-block')

    </div>
</div>
