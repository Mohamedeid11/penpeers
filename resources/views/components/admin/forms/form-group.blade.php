<div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
    <div class="form-group row">
        <label class="col-sm-2 control-label text-sm-right" for="input-{{$name}}">
            <span class="d-flex justify-content-sm-end align-items-center h-100">
                <span class="d-inline-flex"></span>
                <span class="d-inline-flex text-break">{{$label()}} : </span>
                <span class="d-inline-flex pl-1">{!! $required() ? ' <strong> <b> *</b> </strong>' : '' !!}</span>
            </span>
        </label>
        <div class="col-sm-10 text-left">
            @if ($type() == 'checkbox')
                <input id="input-{{$name}}" type="checkbox" data-toggle="toggle" data-on="{{__('admin.active')}}" name="{{$name}}"
                       data-off="{{__('admin.not_active')}}" data-onstyle="primary" data-offstyle="secondary" {{$isChecked(old($name))}}>
            @elseif ($type() == 'ckeditor')
                <input type="hidden" name="{{$name}}" id="input-{{$name}}" class="ckeditor-input">
                <div data-editor="DecoupledDocumentEditor" data-collaboration="false">
                    <div class="centered">
                        <div class="d-flex py-2">
                            @if($errors->has($name))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get($name) as $error)
                                        <li class="parsley-required">{{$error}}</li>
                                    @endforeach

                                </ul>
                            @endif
                            <a href="#" class="btn btn-info mx-1 ml-auto" title="Save"><i class="fa fa-expand"></i></a>
                        </div>
                        <div class="row">
                            <div class="document-editor__toolbar"></div>
                        </div>
                        <div class="row row-editor">
                            <div class="editor-container">
                                <div id="editor" class="editor" name="content">
                                    {!! $value(old($name)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($type() == 'record')
                <div class="row record-row">
                    <input type="hidden" name="{{$name}}" class="recorded-input">
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center h-100 w-100">
                            <input class="record-input d-none" type="file" accept="audio/*" capture>
                            <button class="btn btn-lg btn-outline-success record-upload mr-2"><i class="mdi mdi-upload"></i> {{__('admin.upload')}}</button>
                            <button class="btn btn-lg btn-outline-warning record-mic"><i class="mdi mdi-microphone">
                                {{__('admin.record')}}</i></button>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="not-selected indicators border-bottom h-100 @if($item->exists) d-none @endif">
                            <div class="d-flex align-items-center h-100">
                                <span class="d-inline-flex">Press record or upload your recording !</span>
                            </div>
                        </div>
                        <div class="visualizer indicators h-100 d-none">
                            <div class="d-flex align-items-center h-100">
                                <button class="btn btn-danger recorder-controls recorder-stop mr-2 d-none"><i class="mdi mdi-stop"></i></button>
                                <canvas id="meter-{{$name}}" class="w-auto" height="30"></canvas>
                            </div>
                        </div>
                        <div class="player indicators @if(!$item->exists) d-none @endif">
                            <div class="waveform" data-value="{{asset($value(old($name)))}}"></div>
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <button class="btn btn-success player-controls player-resume rounded mr-2 d-none"><i class="mdi mdi-play"></i></button>
                                <button class="btn btn-warning player-controls player-pause rounded mr-2 d-none"><i class="mdi mdi-pause"></i></button>
                                <button class="btn btn-danger player-controls player-stop mr-2 d-none"><i class="mdi mdi-stop"></i></button>
                            </div>
                        </div>
                    </div>
                    @if($errors->has($name))
                        <div class="col-12">
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get($name) as $error)
                                    <li class="parsley-required">{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @elseif ($type() == 'select')
                <select id="input-{{$name}}" class="form-control {{$errorClasses($errors)}}" name="{{$name}}">
                    @foreach($field->get('choices') as $key => $choice)
                        <option value="{{$key}}" {{$isSelected($key, old($name))}}>{{$choice}}</option>
                    @endforeach
                </select>
                @if($errors->has($name))
                    <ul class="parsley-errors-list filled">
                        @foreach($errors->get($name) as $error)
                            <li class="parsley-required">{{$error}}</li>
                        @endforeach

                    </ul>
                @endif
            @elseif($type() == 'textarea')
                <textarea class="form-control text-left {{$errorClasses($errors)}}"
                          id="input-{{$name}}" name="{{$name}}" {{$required()}}
                          placeholder="{{__('admin.placeholder_text', ['name'=>$label()])}}
                @if($field->get('comment', null))({{__('admin.forms.'.$field->get('comment', ''))}})@endif">{{$value(old($name))}}</textarea>
                @if($errors->has($name))
                    <ul class="parsley-errors-list filled">
                        @foreach($errors->get($name) as $error)
                            <li class="parsley-required">{{$error}}</li>
                        @endforeach

                    </ul>
                @endif
            @elseif ($type() == 'permissions')
                <div class="row mt-2">
                    @if($errors->has($name))
                        <div class="col-12">
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get($name) as $error)
                                    <li class="parsley-required">=> {{$error}}</li>
                                @endforeach

                            </ul>
                            <br>
                        </div>
                    @endif
                    @foreach(\App\Models\PermissionCategory::all() as $permission_category)
                        <div class="col-6 border-bottom border-right @if($errors->has($name)) border-danger @endif">
                            <label><strong>{{$permission_category->trans('display_name')}}</strong>
                            </label>
                            @foreach($permission_category->permissions as $permission)
                                <div class="checkbox text-left checkbox-primary">
                                    <input id="checkbox-{{$permission->id}}" type="checkbox" value="{{$permission->id}}"
                                           data-value="{{$permission->id}}" name="permissions[]"
                                           @if(old($name))
                                           @if(in_array($permission->id, old($name))) checked @endif
                                           @else
                                           @if($item->exists)
                                           @if(in_array($permission->id, $item->$name->pluck('id')->toArray())) checked @endif
                                        @endif
                                        @endif>
                                    <label for="checkbox-{{$permission->id}}">{{$permission->trans('display_name')}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @else
                @if($isImage())
                    <img src="{{storage_asset($value(old(null)))}}" height="100"><br>
                @endif
                <input type="{{$type()}}" class="form-control text-left {{$errorClasses($errors)}}"
                       id="input-{{$name}}" name="{{$name}}" value="{{$value(old($name))}}" {{$required()}} {{$disabled()}}
                       placeholder="{{__('admin.placeholder_text', ['name'=>$label()])}} @if($field->get('comment', null))({{__('admin.forms.'.$field->get('comment', ''))}})@endif">
                @if($errors->has($name))
                    <ul class="parsley-errors-list filled">
                        @foreach($errors->get($name) as $error)
                            <li class="parsley-required">{{$error}}</li>
                        @endforeach

                    </ul>
                @endif
            @endif
        </div>

    </div>
    {{--    @if(in_array($name, $item->trans_fields))--}}
    {{--        @foreach(languages_list() as $lang)--}}
    {{--            <div class="form-group row">--}}
    {{--                <label class="col-sm-2 control-label text-right" for="input-translation-{{$name}}">--}}
    {{--                    <span class="d-flex justify-content-end align-items-center h-100">--}}
    {{--                        <span class="d-inline-flex"></span>--}}
    {{--                        <span class="d-inline-flex">{{__('admin.translation_in', ['name' => $label(), 'lang'=>__('admin.languages.'.$lang) ])}} :--}}
    {{--                        </span>--}}
    {{--                        <span class="d-inline-flex"></span>--}}
    {{--                    </span>--}}
    {{--                </label>--}}
    {{--                <div class="col-sm-10 text-left">--}}
    {{--                    <input type="{{$type()}}" class="form-control text-left @if($errors->has('translations.'.$name.'.'.$lang)) parsley-error @endif"--}}
    {{--                           id="input-translation-{{$name}}" name="translations[{{$name}}][{{$lang}}]"--}}
    {{--                           placeholder="{{__('admin.placeholder_text', ['name'=>$label()])}}"--}}
    {{--                           @if(old('translations.'.$name.'.'.$lang))--}}
    {{--                           value="{{old('translations.'.$name.'.'.$lang)}}"--}}
    {{--                           @else--}}
    {{--                           @if($item->exists)--}}
    {{--                           value="{{$item->translate($name, $lang)}}"--}}
    {{--                           @else--}}
    {{--                           value=""--}}
    {{--                        @endif--}}
    {{--                        @endif>--}}
    {{--                    @if($errors->has('translations.'.$name.'.'.$lang))--}}
    {{--                        <ul class="parsley-errors-list filled">--}}
    {{--                            @foreach($errors->get('translations.'.$name.'.'.$lang) as $error)--}}
    {{--                                <li class="parsley-required">{{$error}}</li>--}}
    {{--                            @endforeach--}}
    {{--                        </ul>--}}
    {{--                    @endif--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        @endforeach--}}
    {{--    @endif--}}
</div>
