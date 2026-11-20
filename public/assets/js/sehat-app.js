/**
 * Sehat App UI helpers
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    initConfirmDelete();
    initLoadingButtons();
    initOtpInputs();
    initSidebarToggle();
  });

  function initConfirmDelete() {
    document.querySelectorAll('[data-sehat-confirm]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        var message = el.getAttribute('data-sehat-confirm') ||
          'Are you sure you want to delete this record? This action cannot be undone.';
        if (!window.confirm(message)) {
          e.preventDefault();
        }
      });
    });

    document.querySelectorAll('form[data-sehat-confirm-delete]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        var message = form.getAttribute('data-sehat-confirm-delete') ||
          'Are you sure you want to delete this record?';
        if (!window.confirm(message)) {
          e.preventDefault();
        }
      });
    });
  }

  function initLoadingButtons() {
    document.querySelectorAll('form[data-sehat-loading]').forEach(function (form) {
      form.addEventListener('submit', function () {
        var btn = form.querySelector('[type="submit"]');
        if (!btn || btn.classList.contains('btn-sehat-loading')) {
          return;
        }
        btn.classList.add('btn-sehat-loading');
        btn.disabled = true;
        var label = btn.getAttribute('data-loading-text') || 'Please wait…';
        var spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
        btn.innerHTML = spinner + label;
      });
    });
  }

  function initOtpInputs() {
    var groups = document.querySelectorAll('[data-sehat-otp]');
    groups.forEach(function (group) {
      var inputs = group.querySelectorAll('.sehat-otp-input');
      var hidden = document.querySelector(group.getAttribute('data-sehat-otp-target') || '#otp');

      function syncHidden() {
        if (!hidden) return;
        var code = '';
        inputs.forEach(function (inp) {
          code += inp.value;
        });
        hidden.value = code;
      }

      inputs.forEach(function (input, index) {
        input.addEventListener('input', function () {
          input.value = input.value.replace(/\D/g, '').slice(0, 1);
          syncHidden();
          if (input.value && index < inputs.length - 1) {
            inputs[index + 1].focus();
          }
        });

        input.addEventListener('keydown', function (e) {
          if (e.key === 'Backspace' && !input.value && index > 0) {
            inputs[index - 1].focus();
          }
          if (e.key === 'ArrowLeft' && index > 0) {
            inputs[index - 1].focus();
          }
          if (e.key === 'ArrowRight' && index < inputs.length - 1) {
            inputs[index + 1].focus();
          }
        });

        input.addEventListener('paste', function (e) {
          e.preventDefault();
          var pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, inputs.length);
          pasted.split('').forEach(function (ch, i) {
            if (inputs[i]) {
              inputs[i].value = ch;
            }
          });
          syncHidden();
          var focusIndex = Math.min(pasted.length, inputs.length - 1);
          inputs[focusIndex].focus();
        });
      });
    });

    var countdownEl = document.querySelector('[data-sehat-otp-countdown]');
    if (countdownEl) {
      var seconds = parseInt(countdownEl.getAttribute('data-seconds') || '60', 10);
      var resendBtn = document.querySelector('[data-sehat-otp-resend]');

      function tick() {
        if (seconds <= 0) {
          countdownEl.textContent = 'You can resend the code now.';
          if (resendBtn) {
            resendBtn.classList.remove('disabled');
            resendBtn.removeAttribute('aria-disabled');
          }
          return;
        }
        countdownEl.textContent = 'Resend available in ' + seconds + 's';
        seconds--;
        setTimeout(tick, 1000);
      }
      tick();
    }
  }

  function initSidebarToggle() {
    var toggle = document.querySelector('[data-sehat-sidebar-toggle]');
    var sidebar = document.querySelector('.sehat-sidebar');
    if (toggle && sidebar) {
      toggle.addEventListener('click', function () {
        sidebar.classList.toggle('show');
      });
    }
  }
})();