@section('additional_scripts')
<script type="text/javascript">
    window.resourceUrl = '{{ route('cms.roles.index') }}';
</script>
@endsection

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <livewire:cms.nav.breadcrumb :items="$this->breadcrumbItems" />

            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Create New Role</h3>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form class="form" wire:submit.prevent="save">
                        {{ CmsForm::setErrorBag($errors) }}

                        {!! CmsForm::text('role.name') !!}
                        {!! CmsForm::text('role.guard_name') !!}

                        @foreach (config('cms_menu.items') as $item)
                            @if ($this->canManageAtLeastOneNode($item))
                                <div class="mt-16">
                                    <h3>{{ data_get($item, 'title') }} Permissions</h3>
                                </div>

                                <table class="table mt-5 mb-8 table-bordered table-responsive-sm">
                                    <tr>
                                        <th class="text-center">Resource Name</th>
                                        <th class="text-center" style="width: 120px;">View All</th>
                                        <th class="text-center" style="width: 120px;">View One</th>
                                        <th class="text-center" style="width: 120px;">Create</th>
                                        <th class="text-center" style="width: 120px;">Update</th>
                                        <th class="text-center" style="width: 120px;">Delete</th>
                                    </tr>
                                    @foreach ($item['children'] as $child)
                                        @if ($this->canManageNode($child))
                                            <tr>
                                                <td>{{ $this->getPermissionGroupName($child['permission']) }}</td>
                                                <td style="width: 120px;">
                                                    @if (cms_admin()->can('cms.' . $this->getPermissionGroupName($child['permission'], 'raw') . '.viewAny'))
                                                        <label class="checkbox checkbox-outline checkbox-primary" style="width: 20px; overflow: hidden; margin: 0 auto;">
                                                            <input wire:model="permissions" type="checkbox" name="cms-{{ $this->getPermissionGroupName($child['permission'], 'raw') }}-viewAny" value="cms.{{ $this->getPermissionGroupName($child['permission'], 'raw') }}.viewAny"><span></span>
                                                        </label>
                                                    @endif
                                                </td>
                                                <td style="width: 120px;">
                                                    @if (cms_admin()->can('cms.' . $this->getPermissionGroupName($child['permission'], 'raw') . '.view'))
                                                        <label class="checkbox checkbox-outline checkbox-primary" style="width: 20px; overflow: hidden; margin: 0 auto;">
                                                            <input wire:model="permissions" type="checkbox" name="cms-{{ $this->getPermissionGroupName($child['permission'], 'raw') }}-view" value="cms.{{ $this->getPermissionGroupName($child['permission'], 'raw') }}.view"><span></span>
                                                        </label>
                                                    @endif
                                                </td>
                                                <td style="width: 120px;">
                                                    @if (cms_admin()->can('cms.' . $this->getPermissionGroupName($child['permission'], 'raw') . '.create'))
                                                        <label class="checkbox checkbox-outline checkbox-primary" style="width: 20px; overflow: hidden; margin: 0 auto;">
                                                            <input wire:model="permissions" type="checkbox" name="cms-{{ $this->getPermissionGroupName($child['permission'], 'raw') }}-create" value="cms.{{ $this->getPermissionGroupName($child['permission'], 'raw') }}.create"><span></span>
                                                        </label>
                                                    @endif
                                                </td>
                                                <td style="width: 120px;">
                                                    @if (cms_admin()->can('cms.' . $this->getPermissionGroupName($child['permission'], 'raw') . '.update'))
                                                        <label class="checkbox checkbox-outline checkbox-primary" style="width: 20px; overflow: hidden; margin: 0 auto;">
                                                            <input wire:model="permissions" type="checkbox" name="cms-{{ $this->getPermissionGroupName($child['permission'], 'raw') }}-update" value="cms.{{ $this->getPermissionGroupName($child['permission'], 'raw') }}.update"><span></span>
                                                        </label>
                                                    @endif
                                                </td>
                                                <td style="width: 120px;">
                                                    @if (cms_admin()->can('cms.' . $this->getPermissionGroupName($child['permission'], 'raw') . '.delete'))
                                                        <label class="checkbox checkbox-outline checkbox-primary" style="width: 20px; overflow: hidden; margin: 0 auto;">
                                                            <input wire:model="permissions" type="checkbox" name="cms-{{ $this->getPermissionGroupName($child['permission'], 'raw') }}-delete" value="cms.{{ $this->getPermissionGroupName($child['permission'], 'raw') }}.delete"><span></span>
                                                        </label>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            @endif
                        @endforeach

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Save Role</button>
                            <button wire:click="backToIndex()" type="button" class="btn btn-light-primary ml-2">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
