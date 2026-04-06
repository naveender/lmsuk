{{-- FILE: resources\views\livewire\restore-backup.blade.php --}}
<div>
    <div>
        @if ($status === 'idle')
            <div class="card mb-0 d-flex flex-column">
                <div class="card-body d-flex flex-column p-2" style="max-width: 340px;">
                <button wire:click="startRestore" wire:loading.attr="disabled" 
                        class="btn btn-sm btn-outline-primary fw-bold" 
                        {{ $isAnotherRestoreRunning ? 'disabled' : '' }}>
                    <i class="feather icon-database"></i> Restore File
                </button>
                <small class="text-center text-muted mt-1">
                    <span class="fw-semibold">Size:</span> {{ $fileSizeHuman }} &nbsp;•&nbsp; 
                    <span class="fw-semibold">Est. Time:</span> {{ $estimatedTime }}
                </small>
            </div>
            </div>
        @else
        <!--wire:poll.keep-alive.5s="refreshStatus"-->
            <div wire:poll.keep-alive.5s="refreshStatus"> 
                <div class="card mb-0 border-0 shadow-sm" style="max-width: 340px;">
                    <div class="card-body p-2 m-0">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <button class="btn btn-sm {{ $status === 'failed' ? 'btn-danger' : 'btn-primary' }} fw-bold" disabled>
                                        <i class="feather {{ $status === 'failed' ? 'icon-alert-circle' : 'icon-loader' }} {{ $status === 'running' ? 'spinner' : '' }}"></i>
                                        {{ ucfirst($status) }}
                                    </button>
                                </div>
                                
                                @if (in_array($status, ['running', 'queued']))
                                    <button wire:click="cancelRestore" wire:loading.attr="disabled"
                                        class="btn btn-sm btn-danger mr-1 waves-effect waves-light" title="Cancel Restore"><i class="feather icon-x-circle"> Cancel </i>
                                       
                                    </button>
                                @endif
                            </div>

                            <div class="progress" style="height:14px; background:#e9ecef;">
                                <div class="progress-bar progress-bar-striped {{ $status === 'running' || $status === 'transferring' || $status === 'extracting' ? 'progress-bar-animated' : '' }}"
                                     role="progressbar"
                                     style="width: {{ $progress }}%; background: linear-gradient(90deg,#007bff 60%,#17a2b8);"
                                     aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted fw-semibold">
                                    <i class="feather icon-bar-chart-2"></i> {{ $progress }}% Complete
                                </small>
                                @if($status === 'running' && $estimatedTimeRemaining || $status === 'transferring' || $status === 'extracting')
                                    <small class="text-muted fw-semibold">
                                        <i class="feather icon-clock"></i> {{ $estimatedTimeRemaining }} left
                                    </small>
                                @endif
                            </div>

                            @if ($status === 'failed')
                                <div class="text-danger small mt-2 d-flex align-items-center gap-1">
                                    <i class="feather icon-alert-triangle"></i>
                                    <span>Restore failed. Please check the logs or try again.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
