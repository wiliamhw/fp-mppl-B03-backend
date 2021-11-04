
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <livewire:cms.nav.breadcrumb :items="$this->breadcrumbItems" />

            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Update Profile</h3>
                    <div class="card-toolbar">
                        <!--
                        <button class="btn btn-primary">Test button</button>
                        -->
                    </div>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form wire:submit.prevent="save" class="form">
                        {{ CmsForm::setErrorBag($errors) }}

                        {!! CmsForm::text('data.name') !!}
                        {!! CmsForm::password('data.current_password', ['required' => false]) !!}
                        {!! CmsForm::password('data.password', ['required' => false]) !!}
                        {!! CmsForm::password('data.password_confirmation', ['required' => false]) !!}

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
