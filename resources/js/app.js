import './bootstrap';

import collapse from '@alpinejs/collapse';
import sort from '@alpinejs/sort';

document.addEventListener('alpine:init', () => {
    Alpine.plugin(collapse);
    Alpine.plugin(sort);
});

document.addEventListener('livewire:init', () => {
    let activeRequests = 0;

    Livewire.hook('request', ({ succeed, fail }) => {
        activeRequests++;
        window.dispatchEvent(new CustomEvent('loading-bar-start'));

        const stop = () => {
            activeRequests = Math.max(0, activeRequests - 1);
            if (activeRequests === 0) {
                window.dispatchEvent(new CustomEvent('loading-bar-stop'));
            }
        };

        succeed(stop);
        fail(stop);
    });
});

/**
 * Promote the primary page-view select into the same first-class tab strip used
 * by Orders. The select remains the Livewire source of truth, so filtering,
 * query-string state, validation, and browser navigation continue to work.
 */
const enhanceAdminTabs = () => {
    const candidates = document.querySelectorAll('.admin-page-head .admin-filter-row select:first-of-type, .admin-page-head > div > select[wire\\:model\\.live="status"]');

    candidates.forEach((select) => {
        const page = select.closest('.admin-page');
        const header = select.closest('.admin-page-head');
        if (!page || !header || page.querySelector(':scope > .admin-page-main > .admin-tab-row') || select.dataset.adminTabs === 'ready') return;

        const nav = document.createElement('nav');
        nav.className = 'admin-tab-row';
        nav.setAttribute('aria-label', 'Page views');

        [...select.options].forEach((option) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `admin-tab${option.value === select.value ? ' is-active' : ''}`;
            button.textContent = option.textContent;
            button.addEventListener('click', () => {
                select.value = option.value;
                select.dispatchEvent(new Event('input', { bubbles: true }));
                select.dispatchEvent(new Event('change', { bubbles: true }));
                nav.querySelectorAll('.admin-tab').forEach((tab) => tab.classList.remove('is-active'));
                button.classList.add('is-active');
            });
            nav.appendChild(button);
        });

        select.dataset.adminTabs = 'ready';
        select.classList.add('admin-tab-source');
        header.insertAdjacentElement('afterend', nav);
    });
};

const enhanceAdminTables = () => {
    document.querySelectorAll('.admin-table').forEach((table) => {
        const heading = table.tHead?.rows?.[0];
        if (!heading) return;

        const rowChecks = [...table.querySelectorAll('tbody input[type="checkbox"]')];
        if (rowChecks.length && !heading.querySelector('input[type="checkbox"]')) {
            const firstHeading = heading.cells[0];
            if (firstHeading) {
                const selectAll = document.createElement('input');
                selectAll.type = 'checkbox';
                selectAll.setAttribute('aria-label', 'Select all visible rows');
                selectAll.addEventListener('change', () => {
                    rowChecks.forEach((checkbox) => {
                        if (checkbox.checked === selectAll.checked) return;
                        checkbox.checked = selectAll.checked;
                        checkbox.dispatchEvent(new Event('input', { bubbles: true }));
                        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                });
                firstHeading.replaceChildren(selectAll);
            }
        }

        [...table.tBodies].flatMap((body) => [...body.rows]).forEach((row) => {
            if (row.dataset.adminTableRow === 'ready' || row.cells.length < 2) return;
            row.dataset.adminTableRow = 'ready';
            const cell = row.cells[row.cells.length - 1];
            const actions = [...cell.children].filter((child) => child.matches('a, button'));
            if (!actions.length || cell.querySelector('.admin-row-menu')) return;
            if (actions.length === 1 && /(?:•••|…)/.test(actions[0].textContent.trim())) return;

            const menu = document.createElement('details');
            menu.className = 'admin-row-menu';
            const trigger = document.createElement('summary');
            trigger.textContent = '•••';
            trigger.setAttribute('aria-label', 'Row actions');
            const list = document.createElement('div');
            list.className = 'admin-row-menu-list';
            actions.forEach((action) => list.appendChild(action));
            menu.append(trigger, list);
            cell.appendChild(menu);
        });
    });
};

const placeOverviewTrigger = () => {
    const trigger = document.querySelector('.admin-overview-trigger');
    const headingRow = document.querySelector('.admin-page-head > div:first-child');
    if (!trigger || !headingRow || headingRow.querySelector('.admin-overview-inline')) return;

    let actions = headingRow.querySelector(':scope > .admin-page-actions, :scope > .admin-heading-actions');
    if (!actions) {
        actions = document.createElement('div');
        actions.className = 'admin-heading-actions';
        headingRow.appendChild(actions);
    }
    const inlineTrigger = document.createElement('button');
    inlineTrigger.type = 'button';
    inlineTrigger.className = 'admin-overview-trigger admin-overview-inline';
    inlineTrigger.innerHTML = trigger.innerHTML;
    inlineTrigger.setAttribute('aria-label', 'Open page overview');
    inlineTrigger.addEventListener('click', () => window.dispatchEvent(new CustomEvent('toggle-overview')));
    actions.prepend(inlineTrigger);
    trigger.classList.add('admin-overview-source');
};

const enhanceAdminWorkspace = () => {
    enhanceAdminTabs();
    enhanceAdminTables();
    placeOverviewTrigger();
};

document.addEventListener('DOMContentLoaded', enhanceAdminWorkspace);
document.addEventListener('livewire:navigated', enhanceAdminWorkspace);
new MutationObserver(enhanceAdminWorkspace).observe(document.documentElement, { childList: true, subtree: true });

window.initCheckoutStripe = function (clientSecret) {
    const stripeKey = document.querySelector('meta[name="stripe-key"]').content;
    const stripe = Stripe(stripeKey);
    const elements = stripe.elements({ clientSecret });
    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');

    const button = document.getElementById('submit-payment');
    const errorBox = document.getElementById('payment-error');

    button.addEventListener('click', async () => {
        button.disabled = true;
        button.textContent = 'Processing...';
        errorBox.classList.add('hidden');

        const { error, paymentIntent } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: window.location.origin + '/checkout/return',
            },
            redirect: 'if_required',
        });

        if (error) {
            errorBox.textContent = error.message;
            errorBox.classList.remove('hidden');
            button.disabled = false;
            button.textContent = 'Try Again';
            return;
        }

        if (paymentIntent && paymentIntent.status === 'succeeded') {
            window.location.href = '/checkout/success?payment_intent=' + paymentIntent.id;
        }
    });
};
