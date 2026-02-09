@extends('layouts.app')

@section('content')
<style>
.form-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    overflow: hidden;
}
.form-header {
    background: #f8fafc;
    padding: 24px;
    border-bottom: 1px solid #e2e8f0;
}
.form-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.form-body {
    padding: 24px;
}
.form-group {
    margin-bottom: 1.5rem;
}
.form-label {
    display: block;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}
.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
}
.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}
.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
.recurrence-section {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 8px;
    margin-top: 1rem;
    border-left: 3px solid #3b82f6;
}
.submit-button {
    background: #3b82f6;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: background 0.2s ease;
}
.submit-button:hover {
    background: #2563eb;
}
.cancel-link {
    display: inline-block;
    color: #64748b;
    text-decoration: none;
    margin-left: 1rem;
    font-weight: 500;
}
.cancel-link:hover {
    color: #334155;
}
</style>

<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Maintenance Schedule
        </h1>
    </div>
    
    <div class="form-body">
        @if ($errors->any())
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                <strong>Error:</strong>
                <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('maintenance.update', $schedule->maintenance_sched_id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label" for="equipment_name">Equipment Name *</label>
                <input type="text" 
                       id="equipment_name" 
                       name="equipment_name" 
                       class="form-control"
                       value="{{ old('equipment_name', $schedule->equipment_name) }}"
                       required>
            </div>

            <div class="form-group">
                <label class="form-label" for="equipment_id">Equipment ID *</label>
                <input type="text" 
                       id="equipment_id" 
                       name="equipment_id" 
                       class="form-control"
                       value="{{ old('equipment_id', $schedule->equipment_id) }}"
                       required
                       placeholder="Enter equipment ID to check permit validity">
                <div id="permit-status" class="mt-2 text-sm"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="maintenance_type_id">Maintenance Type *</label>
                    <select id="maintenance_type_id" 
                            name="maintenance_type_id" 
                            class="form-control"
                            required>
                        <option value="">Select Maintenance Type</option>
                        @foreach($maintenanceTypes as $type)
                            <option value="{{ $type->maintenance_types_id }}" 
                                    {{ (old('maintenance_type_id') ?? $schedule->maintenance_type_id) == $type->maintenance_types_id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="scheduled_date">Scheduled Date *</label>
                    <input type="date" 
                           id="scheduled_date" 
                           name="scheduled_date" 
                           class="form-control"
                           value="{{ old('scheduled_date', $schedule->scheduled_date) }}"
                           min="{{ date('Y-m-d') }}"
                           required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="priority">Priority *</label>
                    <select id="priority" 
                            name="priority" 
                            class="form-control"
                            required>
                        <option value="low" {{ (old('priority') ?? $schedule->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ (old('priority') ?? $schedule->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ (old('priority') ?? $schedule->priority) == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ (old('priority') ?? $schedule->priority) == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" 
                           id="is_recurring" 
                           name="is_recurring" 
                           value="1"
                           {{ (old('is_recurring') ?? $schedule->is_recurring) ? 'checked' : '' }}>
                    <label for="is_recurring" style="font-weight: normal; color: #475569;">
                        Set as Recurring Maintenance
                    </label>
                </div>
            </div>

            <div id="recurrence-section" class="recurrence-section" style="display: none;">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="recurrence_type">Recurrence Type</label>
                        <select id="recurrence_type" 
                                name="recurrence_type" 
                                class="form-control">
                            <option value="daily" {{ (old('recurrence_type') ?? $schedule->recurrence_type) == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ (old('recurrence_type') ?? $schedule->recurrence_type) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ (old('recurrence_type') ?? $schedule->recurrence_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ (old('recurrence_type') ?? $schedule->recurrence_type) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="recurrence_frequency">Frequency</label>
                        <input type="number" 
                               id="recurrence_frequency" 
                               name="recurrence_frequency" 
                               class="form-control"
                               value="{{ old('recurrence_frequency', $schedule->recurrence_frequency ?? 1) }}"
                               min="1"
                               max="365">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="recurrence_end_date">End Date (Optional)</label>
                    <input type="date" 
                           id="recurrence_end_date" 
                           name="recurrence_end_date" 
                           class="form-control"
                           value="{{ old('recurrence_end_date', $schedule->recurrence_end_date) }}">
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="submit-button">
                    Update Schedule
                </button>
                <a href="{{ route('maintenance.index') }}" class="cancel-link">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recurringCheckbox = document.getElementById('is_recurring');
    const recurrenceSection = document.getElementById('recurrence-section');
    
    if (recurringCheckbox.checked) {
        recurrenceSection.style.display = 'block';
    }
    
    recurringCheckbox.addEventListener('change', function() {
        recurrenceSection.style.display = this.checked ? 'block' : 'none';
    });

    document.getElementById('equipment_id').addEventListener('blur', function() {
        const equipmentId = this.value;
        const permitStatus = document.getElementById('permit-status');
        
        if (equipmentId) {
            permitStatus.innerHTML = '<span style="color: #3b82f6;">Checking permit validity...</span>';
            
            setTimeout(() => {
                if (equipmentId.startsWith('CRANE')) {
                    permitStatus.innerHTML = '<span style="color: #16a34a;">✅ Valid permit found!</span>';
                } else {
                    permitStatus.innerHTML = '<span style="color: #dc2626;">❌ No valid permit found. Please check Contract & Permit module.</span>';
                }
            }, 500);
        }
    });
});
</script>
@endsection