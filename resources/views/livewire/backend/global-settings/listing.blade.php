<div>

    {{-- NAV TABS --}}
    <ul class="nav nav-tabs mb-3" role="tablist">
        @foreach ($settingList as $group => $settings)
            <li class="nav-item">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab" href="#tab-{{ $group }}">
                    {{ ucfirst($group ?? 'general') }}
                </a>
            </li>
        @endforeach
    </ul>

    {{-- TAB CONTENT --}}
    <div class="tab-content">

        @foreach ($settingList as $group => $settings)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $group }}">

                <table class="table table-bordered">
                    <tbody>

                        @foreach ($settings as $setting)
                            <tr>
                                <td width="25%">
                                    <label>{{ $setting->label }}</label>
                                </td>

                                <td>
                                    @switch($setting->input_type)
                                        @case('text')
                                        @case('email')

                                        @case('number')
                                        @case('url')
                                            <input type="{{ $setting->input_type }}" class="form-control"
                                                wire:model.defer="values.{{ $setting->id }}">
                                        @break

                                        @case('textarea')
                                            <textarea class="form-control" rows="2" wire:model.defer="values.{{ $setting->id }}">
                                        </textarea>
                                        @break

                                        @case('select')
                                            <select class="form-control" wire:model.defer="values.{{ $setting->id }}">

                                                <option value="">Select {{ $setting->label }}</option>

                                                @foreach (json_decode($setting->options, true) ?? [] as $option)
                                                    <option value="{{ $option }}">
                                                        {{ $option }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        @break

                                        @case('checkbox')
                                            <input type="checkbox" wire:model.defer="values.{{ $setting->id }}">
                                        @break

                                        @default
                                            <input type="text" class="form-control"
                                                wire:model.defer="values.{{ $setting->id }}">
                                    @endswitch
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
    {{-- SAVE BUTTON --}}
    <div class="text-right mt-3">
        <button wire:click="saveAll" class="btn btn-success" wire:loading.attr="disabled">
            <span wire:loading.remove>Save All Changes</span>
            <span wire:loading>Saving...</span>
        </button>
    </div>
</div>
