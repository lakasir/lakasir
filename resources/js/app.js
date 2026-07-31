let selectedDevice = null;

function getPrinter() {
  if (localStorage.printer == undefined) {
    console.error('printer didn\'t set');
    return Error('printer didn\'t set');
  }

  return JSON.parse(localStorage.printer);
}

async function printToUSBPrinter(text) {
  let receiptText = text;
  console.log(receiptText);

  try {
    if (localStorage.printer == undefined) {
      console.error('No USB printer selected');
      return;
    }

    let printer = JSON.parse(localStorage.printer);
    const devices = await navigator.usb.getDevices();

    const device = devices.find(device => device.vendorId === printer.vendorId);
    if (device) {
      console.log('Found USB device:', device.productName);

      await device.open();
      await device.selectConfiguration(1);
      await device.claimInterface(0);

      const encoder = new TextEncoder();
      const data = encoder.encode(receiptText);
      const endpoint = device.configuration.interfaces[0].alternate.endpoints.filter(endpoint => endpoint.direction === 'out')[0]
      await device.transferOut(endpoint.endpointNumber, data);

      console.log('Data sent to printer');
    } else {
      console.log('No USB device with the specified vendor ID found');
      new FilamentNotification()
        .title('You should choose the printer first in printer setting')
        .danger()
        .actions([
          new FilamentNotificationAction('Setting')
            .icon('heroicon-o-cog-6-tooth')
            .button()
            .url('/member/printer'),
        ])
        .send()
    }
  } catch (e) {
    console.error(e);
  }
}

function padText(text, length, alignRight = false, center = false, textSize = 'normal') {
  const sizes = {
    'normal': '\x1D\x21\x00', // Normal text
    'large': '\x1D\x21\x11', // Large text
  }[textSize];
  let paddedText = text;

  if (center) {
    const padLength = Math.max(0, length - text.length);
    const padStart = Math.floor(padLength / 2);
    const padEnd = Math.ceil(padLength / 2);
    paddedText = ' '.repeat(padStart) + text + ' '.repeat(padEnd);
  } else if (alignRight) {
    paddedText = text.padStart(length);
  } else {
    paddedText = text.padEnd(length);
  }

  return paddedText;
}

function moneyFormat(number, currency = null) {
  const formatter = new Intl.NumberFormat({
    style: 'currency',
    currency: currency,
  });

  return formatter.format(number);
}

/* ============================================================
   GLOBAL TOOLTIP SYSTEM
   Auto-attaches to elements with [aria-label] or [title].
   Uses position:fixed so overflow:clip never hides it.
   ============================================================ */
(function initTooltips() {
    const tip = document.createElement('div');
    tip.id = 'fi-custom-tooltip';
    Object.assign(tip.style, {
        position:      'fixed',
        top:           '0',
        left:          '0',
        background:    '#1F2937',
        color:         '#F9FAFB',
        fontFamily:    "'Inter', system-ui, sans-serif",
        fontSize:      '12px',
        fontWeight:    '500',
        lineHeight:    '1.4',
        padding:       '5px 10px',
        borderRadius:  '7px',
        pointerEvents: 'none',
        opacity:       '0',
        transition:    'opacity 0.15s ease',
        zIndex:        '99999',
        whiteSpace:    'nowrap',
        boxShadow:     '0 2px 10px rgba(0,0,0,0.25)',
        maxWidth:      '220px',
        textAlign:     'center',
    });
    document.body.appendChild(tip);

    let hideTimer;

    function positionTip(e) {
        const tw = tip.offsetWidth;
        const th = tip.offsetHeight;
        const vw = window.innerWidth;
        let left = e.clientX - tw / 2;
        let top  = e.clientY - th - 12;
        if (left < 8) left = 8;
        if (left + tw > vw - 8) left = vw - tw - 8;
        if (top < 8) top = e.clientY + 20;
        tip.style.left = left + 'px';
        tip.style.top  = top  + 'px';
    }

    function shouldSkip(el) {
        const tag = el.tagName.toLowerCase();
        if (['input', 'select', 'textarea', 'option'].includes(tag)) return true;
        const text = (el.innerText || '').trim();
        if (text.length > 30) return true;
        return false;
    }

    function attachTooltips() {
        const sel = '[aria-label],[title],[data-tooltip]';
        document.querySelectorAll(sel).forEach(function(el) {
            if (el.dataset.tipReady) return;
            el.dataset.tipReady = '1';
            if (shouldSkip(el)) return;
            const label = el.getAttribute('aria-label') ||
                          el.dataset.tooltip ||
                          el.dataset.nativeTitle;
            if (!label) return;
            // Remove native title to avoid browser double-tooltip
            if (el.hasAttribute('title')) {
                el.dataset.nativeTitle = el.getAttribute('title');
                el.removeAttribute('title');
            }
            el.addEventListener('mouseenter', function(e) {
                clearTimeout(hideTimer);
                tip.textContent = label;
                tip.style.opacity = '1';
                positionTip(e);
            });
            el.addEventListener('mousemove', positionTip);
            el.addEventListener('mouseleave', function() {
                hideTimer = setTimeout(function(){ tip.style.opacity = '0'; }, 80);
            });
            el.addEventListener('click', function() {
                tip.style.opacity = '0';
            });
        });
    }

    function boot() {
        attachTooltips();
        var obs = new MutationObserver(function() {
            clearTimeout(obs._t);
            obs._t = setTimeout(attachTooltips, 250);
        });
        obs.observe(document.body, { childList: true, subtree: true });
        document.addEventListener('livewire:navigated', attachTooltips);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();

/* ============================================================
   DROPDOWN FIX
   1. Click-outside: close open panels when clicking elsewhere
   2. Reposition: clamp panel within viewport bounds
   ============================================================ */
(function fixDropdowns() {
    function repositionPanel(panel) {
        var rect = panel.getBoundingClientRect();
        var vw = window.innerWidth;
        var vh = window.innerHeight;
        var margin = 8;

        // Clamp right edge
        if (rect.right > vw - margin) {
            var newLeft = Math.max(margin, (parseFloat(panel.style.left) || 0) - (rect.right - vw + margin));
            panel.style.left = newLeft + 'px';
        }
        // Clamp bottom edge
        if (rect.bottom > vh - margin) {
            var newTop = Math.max(margin, (parseFloat(panel.style.top) || 0) - (rect.bottom - vh + margin));
            panel.style.top = newTop + 'px';
        }
        // Clamp left edge
        rect = panel.getBoundingClientRect();
        if (rect.left < margin) {
            panel.style.left = (parseFloat(panel.style.left) || 0) + (margin - rect.left) + 'px';
        }
    }

    // Watch for panels becoming visible and reposition them
    var posObs = new MutationObserver(function(mutations) {
        mutations.forEach(function(m) {
            m.addedNodes.forEach(function(node) {
                if (node.classList && node.classList.contains('fi-dropdown-panel')) {
                    setTimeout(function() { repositionPanel(node); }, 10);
                }
            });
            if (m.type === 'attributes' && m.target.classList && m.target.classList.contains('fi-dropdown-panel')) {
                if (m.target.style.display !== 'none') {
                    setTimeout(function() { repositionPanel(m.target); }, 10);
                }
            }
        });
    });

    function bootDropdownFix() {
        posObs.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['style'] });

        // Click-outside: close panels when clicking outside them
        document.addEventListener('click', function(e) {
            // Check if click is inside a dropdown panel or its trigger
            if (e.target.closest('.fi-dropdown-panel')) return;
            if (e.target.closest('.fi-dropdown')) return;
            if (e.target.closest('[x-data*="isOpen"]')) return;

            // Find all visible panels and try to close them via Alpine
            document.querySelectorAll('.fi-dropdown-panel').forEach(function(panel) {
                var style = window.getComputedStyle(panel);
                if (style.display === 'none' || style.visibility === 'hidden' || parseFloat(style.opacity) === 0) return;

                // Walk up to find Alpine component with open state
                var el = panel;
                while (el) {
                    if (el._x_dataStack) {
                        var data = el._x_dataStack[0];
                        if (data && typeof data.close === 'function') {
                            data.close();
                            return;
                        }
                        if (data && 'open' in data) {
                            data.open = false;
                            return;
                        }
                    }
                    el = el.parentElement;
                }

                // Fallback: hide the panel directly and dispatch close event
                panel.style.display = 'none';
            });
        }, true); // capture phase to fire before other handlers
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootDropdownFix);
    } else {
        bootDropdownFix();
    }
})();
