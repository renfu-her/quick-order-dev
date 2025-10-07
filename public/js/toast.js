/**
 * Toast Notification System
 * 全局通知系統，支援從右側彈出，5秒自動消失或點擊X關閉
 */

// Toast 配置
const TOAST_CONFIG = {
    duration: 5000, // 5 秒自動消失
    icons: {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    },
    titles: {
        success: 'Success',
        error: 'Error',
        warning: 'Warning',
        info: 'Information'
    }
};

// Toast 管理器
class ToastManager {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // 創建 toast 容器
        if (!document.getElementById('toast-container')) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('toast-container');
        }
    }

    show(message, type = 'info', title = null, duration = TOAST_CONFIG.duration) {
        // 創建 toast 元素
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        // 使用默認標題或自定義標題
        const toastTitle = title || TOAST_CONFIG.titles[type] || 'Notification';
        
        // Toast HTML 結構
        toast.innerHTML = `
            <div class="toast-icon">${TOAST_CONFIG.icons[type] || '📢'}</div>
            <div class="toast-content">
                <p class="toast-title">${toastTitle}</p>
                <p class="toast-message">${message}</p>
            </div>
            <button class="toast-close" aria-label="Close">×</button>
            <div class="toast-progress"></div>
        `;
        
        // 添加到容器
        this.container.appendChild(toast);
        
        // 觸發動畫
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // 關閉按鈕事件
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            this.hide(toast);
        });
        
        // 自動消失
        if (duration > 0) {
            setTimeout(() => {
                this.hide(toast);
            }, duration);
        }
        
        return toast;
    }

    hide(toast) {
        toast.classList.remove('show');
        toast.classList.add('hide');
        
        // 動畫結束後移除元素
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    success(message, title = null, duration = TOAST_CONFIG.duration) {
        return this.show(message, 'success', title, duration);
    }

    error(message, title = null, duration = TOAST_CONFIG.duration) {
        return this.show(message, 'error', title, duration);
    }

    warning(message, title = null, duration = TOAST_CONFIG.duration) {
        return this.show(message, 'warning', title, duration);
    }

    info(message, title = null, duration = TOAST_CONFIG.duration) {
        return this.show(message, 'info', title, duration);
    }
}

// 創建全局實例
window.Toast = new ToastManager();

// 處理 Laravel session flash messages
document.addEventListener('DOMContentLoaded', function() {
    // 檢查是否有 session flash messages
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        let type = 'info';
        let message = alert.textContent.trim();
        
        if (alert.classList.contains('alert-success')) {
            type = 'success';
        } else if (alert.classList.contains('alert-error')) {
            type = 'error';
        } else if (alert.classList.contains('alert-warning')) {
            type = 'warning';
        }
        
        // 顯示 toast
        Toast.show(message, type);
        
        // 移除原始 alert
        alert.remove();
    });
});

// 簡化的全局函數
window.showToast = function(message, type = 'info', title = null) {
    return Toast.show(message, type, title);
};

window.successToast = function(message, title = null) {
    return Toast.success(message, title);
};

window.errorToast = function(message, title = null) {
    return Toast.error(message, title);
};

window.warningToast = function(message, title = null) {
    return Toast.warning(message, title);
};

window.infoToast = function(message, title = null) {
    return Toast.info(message, title);
};
