@extends('layouts.app')

@section('title', 'Collection Entry - Delux')

@section('content')
<div class="animate-in">
    <a href="{{ route('collections.create') }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--accent);font-size:14px;font-weight:600;text-decoration:none;margin-bottom:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
        Back
    </a>

    <h1 class="page-title">{{ $hotel->name }}</h1>
    <p class="page-subtitle">{{ isset($collection) ? 'Update' : 'Enter' }} cloth quantities</p>

    @if($clothTypes->isEmpty())
        <div class="alert alert-warning" style="margin-bottom: 16px;">
            No cloth types are ready yet. Add one below and keep the collection open.
        </div>
    @endif

    <div class="card" style="margin-bottom: 24px; border: 1px dashed var(--border);">
        <div class="section-header" style="margin-bottom: 12px;">
            <span class="section-title">Add Cloth Type</span>
        </div>

        <form id="inlineClothTypeForm" action="{{ route('cloth-types.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Type Name</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="text" name="name" class="form-input" id="inlineClothTypeName" placeholder="e.g. Bedsheet" required>
                    <button type="submit" class="btn btn-secondary" id="inlineClothTypeSubmit">Add</button>
                </div>
                <div id="inlineClothTypeMessage" class="form-help" style="display:none; margin-top: 8px;"></div>
            </div>
        </form>
    </div>

    <form action="{{ route('collections.store', $hotel->id) }}" method="POST">
        @csrf
        <div class="card" style="margin-bottom: 24px;">
            <div class="section-header" style="margin-bottom: 16px;">
                <span class="section-title">Cloth Quantities</span>
            </div>

            <div id="clothTypeRows">
                @forelse($clothTypes as $type)
                    <div class="form-group" data-cloth-type-row="{{ $type->id }}" style="display: flex; align-items: center; justify-content: space-between; gap: 16px; border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 16px;">
                        <label class="form-label" style="margin-bottom: 0; flex: 1;">{{ $type->name }}</label>
                        <input type="number"
                               name="quantities[{{ $type->id }}]"
                               class="form-input"
                               style="width: 100px; text-align: center; font-size: 18px; font-weight: 700; border-color: {{ isset($existingQuantities[$type->id]) ? 'var(--success)' : '' }};"
                               placeholder="0"
                               value="{{ $existingQuantities[$type->id] ?? '' }}"
                               min="0"
                               inputmode="numeric">
                    </div>
                @empty
                    <div id="clothTypeEmptyState" class="empty-state">Add a cloth type above to start this collection.</div>
                @endforelse
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="height: 54px;">{{ isset($collection) ? 'Update Collection' : 'Save Collection' }}</button>
    </form>

    <script>
        const inlineClothTypeForm = document.getElementById('inlineClothTypeForm');
        const inlineClothTypeName = document.getElementById('inlineClothTypeName');
        const inlineClothTypeSubmit = document.getElementById('inlineClothTypeSubmit');
        const inlineClothTypeMessage = document.getElementById('inlineClothTypeMessage');
        const clothTypeRows = document.getElementById('clothTypeRows');
        const clothTypeEmptyState = document.getElementById('clothTypeEmptyState');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function showInlineMessage(message, isError = false) {
            inlineClothTypeMessage.textContent = message;
            inlineClothTypeMessage.style.display = 'block';
            inlineClothTypeMessage.style.color = isError ? 'var(--danger)' : 'var(--success)';
        }

        function createClothTypeRow(id, name) {
            const wrapper = document.createElement('div');
            wrapper.className = 'form-group';
            wrapper.setAttribute('data-cloth-type-row', id);
            wrapper.style.cssText = 'display: flex; align-items: center; justify-content: space-between; gap: 16px; border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 16px;';

            const label = document.createElement('label');
            label.className = 'form-label';
            label.style.cssText = 'margin-bottom: 0; flex: 1;';
            label.textContent = name;

            const input = document.createElement('input');
            input.type = 'number';
            input.name = `quantities[${id}]`;
            input.className = 'form-input';
            input.style.cssText = 'width: 100px; text-align: center; font-size: 18px; font-weight: 700;';
            input.placeholder = '0';
            input.value = '';
            input.min = '0';
            input.inputMode = 'numeric';

            wrapper.appendChild(label);
            wrapper.appendChild(input);
            return wrapper;
        }

        inlineClothTypeForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const name = inlineClothTypeName.value.trim();
            if (!name) {
                showInlineMessage('Please enter a cloth type name.', true);
                return;
            }

            inlineClothTypeSubmit.disabled = true;
            inlineClothTypeSubmit.textContent = 'Adding...';

            try {
                const response = await fetch(inlineClothTypeForm.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ name }),
                });

                const data = await response.json();

                if (!response.ok) {
                    const errorMessage = data?.errors?.name?.[0] || data?.message || 'Could not add cloth type.';
                    throw new Error(errorMessage);
                }

                if (clothTypeEmptyState) {
                    clothTypeEmptyState.remove();
                }

                clothTypeRows.appendChild(createClothTypeRow(data.clothType.id, data.clothType.name));
                showInlineMessage(data.message || 'Cloth type added successfully!');
                inlineClothTypeName.value = '';
                inlineClothTypeName.focus();
            } catch (error) {
                showInlineMessage(error.message || 'Could not add cloth type.', true);
            } finally {
                inlineClothTypeSubmit.disabled = false;
                inlineClothTypeSubmit.textContent = 'Add';
            }
        });
    </script>
</div>
@endsection
