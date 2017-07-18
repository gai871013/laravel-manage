@extends('layouts.a')
@section('section-content')
        <div class="row">
            <div class="col-xs-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#basic" data-toggle="tab">@lang('admin.basic')</a></li>
                        <li><a href="#system" data-toggle="tab">@lang('admin.system')</a></li>
                        <li><a href="#contact" data-toggle="tab">@lang('admin.contactInformation')</a></li>
                        <li><a href="#wechat" data-toggle="tab">@lang('admin.wechat')</a></li>
                    </ul>
                    <form action="{{ route('admin.system.config') }}" method="post"
                          class="form-horizontal form-row-seperated">
                        {{ csrf_field() }}
                        <div class="tab-content">
                            <div class="tab-pane active" id="basic">
                                <div class="col-md-12 box-body">

                                    <!-- Begin: life time stats -->
                                    <div class="portlet">
                                        @if (count($errors) > 0)
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif


                                        <div class="portlet-body">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">@lang('admin.appName'):
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control form-filter"
                                                           name="APP_NAME"
                                                           value="{{ env('APP_NAME','') }}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">@lang('admin.siteTitle'):
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control form-filter"
                                                           name="BASE64_APP_TITLE"
                                                           value="{{ base64_decode(env('BASE64_APP_TITLE','')) }}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">@lang('admin.siteDescription'):
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="col-md-10">
                                <textarea class="form-control maxlength-handler meta_keywords" rows="4"
                                          name="BASE64_APP_DESCRIPTION"
                                          maxlength="1000"
                                          style="resize:none;">{{ base64_decode(env('BASE64_APP_DESCRIPTION','')) }}</textarea>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">@lang('admin.siteKeywords'):
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control form-filter"
                                                           name="BASE64_APP_KEYWORDS"
                                                           value="{{ base64_decode(env('BASE64_APP_KEYWORDS','')) }}">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="system">
                                <div class="col-md-12 box-body">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('index.icp'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="BASE64_ICP"
                                                   value="{{ base64_decode(env('BASE64_ICP','')) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.NumberOfPagesPerPage'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter" name="PAGE_SIZE"
                                                   value="{{ env('PAGE_SIZE','15') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.closeRegistration'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="radio" name="SHOP_REG_CLOSED" id="shop_reg_closed1"
                                                   value="1" <?php if (env
                                                ('SHOP_REG_CLOSED', '') == 1
                                            ) {
                                                echo 'checked';
                                            }
                                                ?> >
                                            <label for="shop_reg_closed1">@lang('admin.yes')</label>
                                            <input type="radio" name="SHOP_REG_CLOSED" id="shop_reg_closed2"
                                                   value="0" <?php if (env
                                                ('SHOP_REG_CLOSED', '') == 0
                                            ) {
                                                echo 'checked';
                                            }
                                                ?>>
                                            <label for="shop_reg_closed2">@lang('admin.no')</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.openDebug'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="radio" name="APP_DEBUG" id="shop_reg_closed1"
                                                   value="true" <?php if (env
                                                ('APP_DEBUG', '') == true
                                            ) {
                                                echo 'checked';
                                            }
                                                ?> >
                                            <label for="shop_reg_closed1">@lang('admin.yes')</label>
                                            <input type="radio" name="APP_DEBUG" id="shop_reg_closed2"
                                                   value="false" <?php if (env
                                                ('APP_DEBUG', '') == false
                                            ) {
                                                echo 'checked';
                                            }
                                                ?>>
                                            <label for="shop_reg_closed2">@lang('admin.no')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="contact">
                                <div class="col-md-12 box-body">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.serviceQQ'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="APP_SERVICE_QQ"
                                                   value="{{ env('APP_SERVICE_QQ','') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.serviceEmail'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="APP_SERVICE_EMAIL"
                                                   value="{{ env('APP_SERVICE_EMAIL','') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.serviceTel'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="APP_SERVICE_PHONE"
                                                   value="{{ env('APP_SERVICE_PHONE','') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('index.address'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="BASE64_ADDRESS"
                                                   value="{{ base64_decode(env('BASE64_ADDRESS','')) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('index.company_name_cn'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="BASE64_APP_COMPANY_NAME_CN"
                                                   value="{{ base64_decode(env('BASE64_APP_COMPANY_NAME_CN','')) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('index.company_name_en'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="BASE64_APP_COMPANY_NAME_EN"
                                                   value="{{ base64_decode(env('BASE64_APP_COMPANY_NAME_EN','')) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="wechat">
                                <div class="col-md-12 box-body">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.wechat_appid'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="WECHAT_APPID"
                                                   value="{{ env('WECHAT_APPID','') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.wechat_secret'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="WECHAT_SECRET"
                                                   value="{{ env('WECHAT_SECRET','') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.wechat_token'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="WECHAT_TOKEN"
                                                   value="{{ env('WECHAT_TOKEN','') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.wechat_aes_key'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="WECHAT_AES_KEY"
                                                   value="{{ env('WECHAT_AES_KEY','') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.wechat_payment_merchant_id'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="WECHAT_PAYMENT_MERCHANT_ID"
                                                   value="{{ env('WECHAT_PAYMENT_MERCHANT_ID','') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.wechat_payment_key'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="WECHAT_PAYMENT_KEY"
                                                   value="{{ env('WECHAT_PAYMENT_KEY','') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.cert_path'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="WECHAT_PAYMENT_CERT_PATH"
                                                   value="{{ env('WECHAT_PAYMENT_CERT_PATH','') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">@lang('admin.wechat_pay_key_path'):
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control form-filter"
                                                   name="WECHAT_PAY_KEY_PATH"
                                                   value="{{ env('WECHAT_PAY_KEY_PATH','') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-2">

                                <div class="actions btn-set">
                                    <a type="button" name="back" onclick="window.history.go(-1)"
                                       class="btn btn-default"><i
                                                class="fa fa-angle-left"></i> 返回</a>
                                    <button class="btn btn-default" type="reset"><i class="fa fa-refresh"></i> 重置
                                    </button>
                                    <button class="btn btn-success ajax-post no-refresh comfirm" type="submit">
                                        <i class="fa fa-check"></i> 保存
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
@endsection
@section('scripts')@endsection