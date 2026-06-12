document.addEventListener('DOMContentLoaded', () => {
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    const modalClosers = document.querySelectorAll('[data-modal-close]');
    const overlays = document.querySelectorAll('.modal-overlay');

    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            const modalId = trigger.getAttribute('data-modal-target');
            const modal = document.getElementById(modalId);
            
            if (['goalDepositModal', 'spendGoalModal', 'deleteGoalModal'].includes(modalId)) {
                const idInput = modal.querySelector('input[name="goal_id"]');
                const nameDisplay = modal.querySelector('.goal-name-display');
                
                if (idInput) idInput.value = trigger.getAttribute('data-goal-id');
                if (nameDisplay) nameDisplay.innerText = trigger.getAttribute('data-goal-name');
            }
            
            if (modal) modal.style.display = 'flex';
        });
    });

    modalClosers.forEach(closer => {
        closer.addEventListener('click', () => {
            const modalId = closer.getAttribute('data-modal-close');
            const modal = document.getElementById(modalId);
            if (modal) modal.style.display = 'none';
        });
    });

    overlays.forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.style.display = 'none';
            }
        });
    });

    const goalTypeSelect = document.getElementById('goalTypeSelect');
    if (goalTypeSelect) {
        goalTypeSelect.addEventListener('change', (e) => {
            const targetGroup = document.getElementById('targetAmountGroup');
            if (e.target.value === 'additional') {
                targetGroup.style.display = 'none';
            } else {
                targetGroup.style.display = 'flex';
            }
        });
    }
});