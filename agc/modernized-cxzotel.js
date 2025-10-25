// modernized-vicidial.js
// Modernized Lifecycle and UI Logic - Retains All Legacy Functionality

(function() {
    'use strict';

    // Store references to original functions if they exist
    var _origBeginRefresh = window.begin_all_refresh;
    var _origLogout = window.BrowserCloseLogout;

    /**
     * PAGE LOAD EVENT
     * Replaces: onload="begin_all_refresh();"
     * Calls original function and hides loading overlay
     */
    window.addEventListener('load', function() {
        console.log('[VICIdial] Page load event triggered');
        
        // Call original begin_all_refresh() if it exists
        if (typeof _origBeginRefresh === 'function') {
            console.log('[VICIdial] Calling original begin_all_refresh()');
            _origBeginRefresh();
        }
        
        // Hide loading overlay after 500ms
        setTimeout(function() {
            var box = document.getElementById('LoadingBox');
            if (box) {
                console.log('[VICIdial] Hiding loading overlay');
                box.classList.add('hidden');
            }
        }, 500);
    });

    /**
     * PAGE BEFORE UNLOAD EVENT
     * Warns user if active call is in progress
     */
    window.addEventListener('beforeunload', function(e) {
        console.log('[VICIdial] Before unload event triggered');
        
        // Check if there's an active call
        if (typeof window.checkActiveCall === 'function' && window.checkActiveCall()) {
            var warningMessage = 'You have an active call. Are you sure you want to leave?';
            e.returnValue = warningMessage;
            console.log('[VICIdial] Warning: Active call detected');
            return warningMessage;
        }
    });

    /**
     * PAGE UNLOAD EVENT
     * Replaces: onunload="BrowserCloseLogout();"
     * Calls original logout function and sends beacon for reliability
     */
    window.addEventListener('unload', function() {
        console.log('[VICIdial] Page unload event triggered - Starting logout');
        
        // Call original BrowserCloseLogout() if it exists
        if (typeof _origLogout === 'function') {
            console.log('[VICIdial] Calling original BrowserCloseLogout()');
            _origLogout();
        }
        
        // Send logout beacon for reliable session cleanup
        if (navigator.sendBeacon && typeof window.session_id !== 'undefined') {
            console.log('[VICIdial] Sending logout beacon with session_id:', window.session_id);
            
            var data = new FormData();
            data.append('function', 'agent_logout');
            data.append('session_id', window.session_id);
            
            navigator.sendBeacon('./api.php', data);
        }
    });

    /**
     * CUSTOM ALERT BOX FUNCTION
     * Replaces: alert_box('BIG ALERT', '36', 'Courier', '#FF0', 'bold|italics');
     * 
     * Usage:
     *   window.alert_box('Message', '36', 'Courier', '#FF0', 'bold|italics');
     * 
     * Parameters:
     *   msg    - Alert message text
     *   size   - Font size in pixels (default: 24)
     *   family - Font family (default: Courier)
     *   bg     - Background color (default: #FFFF00)
     *   style  - Styles: 'bold', 'italics', or 'bold|italics'
     */
    window.alert_box = function(msg, size, family, bg, style) {
        console.log('[VICIdial] Alert box called:', msg);
        
        // Create alert container
        var alertBox = document.createElement('div');
        alertBox.id = 'custom-alert-' + Date.now();
        
        // Build CSS string
        var cssText = 'position:fixed;' +
                     'top:50%;' +
                     'left:50%;' +
                     'transform:translate(-50%,-50%);' +
                     'padding:20px 40px;' +
                     'background-color:' + (bg || '#FFFF00') + ';' +
                     'font-size:' + (size || '24') + 'px;' +
                     'font-family:' + (family || 'Courier') + ';' +
                     'z-index:999999;' +
                     'border:2px solid #000;' +
                     'box-shadow:0 4px 8px rgba(0,0,0,0.3);' +
                     'border-radius:4px;' +
                     'max-width:500px;';
        
        // Apply bold if specified
        if (style && style.indexOf('bold') > -1) {
            cssText += 'font-weight:bold;';
        }
        
        // Apply italics if specified
        if (style && style.indexOf('italics') > -1) {
            cssText += 'font-style:italic;';
        }
        
        alertBox.style.cssText = cssText;
        alertBox.textContent = msg;
        
        // Create close button
        var closeBtn = document.createElement('button');
        closeBtn.textContent = 'X';
        closeBtn.style.cssText = 'position:absolute;' +
                                'top:5px;' +
                                'right:5px;' +
                                'border:none;' +
                                'background:transparent;' +
                                'font-size:20px;' +
                                'cursor:pointer;' +
                                'font-weight:bold;' +
                                'padding:0;' +
                                'width:30px;' +
                                'height:30px;';
        
        // Close button click handler
        closeBtn.onclick = function() {
            console.log('[VICIdial] Alert box closed by user');
            if (document.body.contains(alertBox)) {
                document.body.removeChild(alertBox);
            }
        };
        
        alertBox.appendChild(closeBtn);
        document.body.appendChild(alertBox);
        
        // Auto-remove alert after 5 seconds
        setTimeout(function() {
            if (document.body.contains(alertBox)) {
                console.log('[VICIdial] Alert box auto-closed after 5 seconds');
                document.body.removeChild(alertBox);
            }
        }, 5000);
    };

    /**
     * CONFETTI ANIMATION FUNCTION
     * Replaces: <!-- <canvas id="confetti-canvas"></canvas> -->
     * 
     * Usage:
     *   window.initConfetti();
     * 
     * Note: Requires <canvas id="confetti-canvas"></canvas> in HTML
     */
    window.initConfetti = function() {
        console.log('[VICIdial] Initializing confetti animation');
        
        var canvas = document.getElementById('confetti-canvas');
        if (!canvas) {
            console.warn('[VICIdial] Confetti canvas not found - ensure <canvas id="confetti-canvas"></canvas> exists');
            return;
        }
        
        var ctx = canvas.getContext('2d');
        if (!ctx) {
            console.error('[VICIdial] Cannot get canvas 2D context');
            return;
        }
        
        // Set canvas size to window size
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
        // Initialize confetti pieces
        var confetti = [];
        var colors = ['#7cf27c', '#f2f27c', '#f27c7c', '#7c7cf2', '#f27cf2'];
        var confettiCount = 150;
        
        // Create confetti pieces
        for (var i = 0; i < confettiCount; i++) {
            confetti.push({
                color: colors[Math.floor(Math.random() * colors.length)],
                x: Math.random() * canvas.width,
                y: canvas.height,
                vx: Math.random() * 6 - 3,  // Horizontal velocity
                vy: Math.random() * -15 - 15, // Vertical velocity (upward)
                w: Math.random() * 10 + 5,  // Width
                h: Math.random() * 10 + 15  // Height
            });
        }
        
        // Animation loop
        var render = function() {
            // Clear canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Update and draw each confetti piece
            for (var i = 0; i < confetti.length; i++) {
                var c = confetti[i];
                
                // Apply gravity
                c.vy += 0.5;
                c.vy = Math.min(c.vy, 5); // Terminal velocity
                
                // Apply wind/drift
                c.vx += Math.random() * 0.4 - 0.2;
                
                // Update position
                c.x += c.vx;
                c.y += c.vy;
                
                // Remove if below screen
                if (c.y >= canvas.height) {
                    confetti.splice(i, 1);
                    i--;
                    continue;
                }
                
                // Draw confetti
                ctx.fillStyle = c.color;
                ctx.fillRect(c.x - c.w / 2, c.y - c.h / 2, c.w, c.h);
            }
            
            // Continue animation if confetti remains
            if (confetti.length > 0) {
                requestAnimationFrame(render);
            } else {
                console.log('[VICIdial] Confetti animation complete');
            }
        };
        
        // Start animation
        render();
    };

    /**
     * HANDLE WINDOW RESIZE FOR CANVAS
     * Resize canvas when window resizes
     */
    window.addEventListener('resize', function() {
        var canvas = document.getElementById('confetti-canvas');
        if (canvas) {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
    });

    // Log that modernized script has loaded
    console.log('[VICIdial] Modernized lifecycle manager loaded successfully');
    console.log('[VICIdial] Available functions:');
    console.log('  - window.alert_box(msg, size, family, bg, style)');
    console.log('  - window.initConfetti()');

})();
