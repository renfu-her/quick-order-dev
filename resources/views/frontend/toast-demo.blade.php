@extends('layouts.app')

@section('title', 'Toast Notification Demo')

@push('styles')
<style>
.demo-container {
    max-width: 800px;
    margin: 4rem auto;
    padding: 2rem;
}

.demo-section {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.demo-section h2 {
    color: #333;
    margin-bottom: 1rem;
    border-bottom: 2px solid #f77f00;
    padding-bottom: 0.5rem;
}

.demo-section p {
    color: #666;
    margin-bottom: 1.5rem;
}

.button-group {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.demo-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
}

.demo-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-error {
    background: #ef4444;
    color: white;
}

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-info {
    background: #3b82f6;
    color: white;
}

.code-block {
    background: #f5f5f5;
    border-left: 4px solid #f77f00;
    padding: 1rem;
    margin: 1rem 0;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    overflow-x: auto;
}
</style>
@endpush

@section('content')

<div class="demo-container">
    <div class="demo-section">
        <h1 style="color: #f77f00; margin-bottom: 1rem;">🎉 Toast Notification System Demo</h1>
        <p>這是一個全局 Toast 通知系統的演示頁面。點擊下方按鈕查看不同類型的通知效果。</p>
    </div>

    <div class="demo-section">
        <h2>基本用法</h2>
        <p>四種基本類型的 Toast 通知：</p>
        <div class="button-group">
            <button class="demo-btn btn-success" onclick="successToast('操作成功！')">
                ✅ Success Toast
            </button>
            <button class="demo-btn btn-error" onclick="errorToast('發生錯誤！')">
                ❌ Error Toast
            </button>
            <button class="demo-btn btn-warning" onclick="warningToast('這是一個警告！')">
                ⚠️ Warning Toast
            </button>
            <button class="demo-btn btn-info" onclick="infoToast('這是一則資訊。')">
                ℹ️ Info Toast
            </button>
        </div>
        <div class="code-block">
successToast('操作成功！');<br>
errorToast('發生錯誤！');<br>
warningToast('這是一個警告！');<br>
infoToast('這是一則資訊。');
        </div>
    </div>

    <div class="demo-section">
        <h2>自定義標題</h2>
        <p>為 Toast 添加自定義標題：</p>
        <div class="button-group">
            <button class="demo-btn btn-success" onclick="successToast('訂單已成功建立！', '訂單確認')">
                Success with Title
            </button>
            <button class="demo-btn btn-error" onclick="errorToast('付款處理失敗，請重試', '付款錯誤')">
                Error with Title
            </button>
            <button class="demo-btn btn-warning" onclick="warningToast('庫存即將不足', '庫存警告')">
                Warning with Title
            </button>
            <button class="demo-btn btn-info" onclick="infoToast('您的請求正在處理中...', '處理中')">
                Info with Title
            </button>
        </div>
        <div class="code-block">
successToast('訂單已成功建立！', '訂單確認');<br>
errorToast('付款處理失敗，請重試', '付款錯誤');
        </div>
    </div>

    <div class="demo-section">
        <h2>實際應用場景</h2>
        <p>模擬真實使用情境：</p>
        <div class="button-group">
            <button class="demo-btn btn-success" onclick="demoAddToCart()">
                🛒 加入購物車
            </button>
            <button class="demo-btn btn-success" onclick="demoPlaceOrder()">
                📦 下訂單
            </button>
            <button class="demo-btn btn-error" onclick="demoLoginFailed()">
                🔒 登入失敗
            </button>
            <button class="demo-btn btn-warning" onclick="demoLowStock()">
                📊 庫存不足
            </button>
            <button class="demo-btn btn-info" onclick="demoProcessing()">
                ⏳ 處理中
            </button>
        </div>
    </div>

    <div class="demo-section">
        <h2>多個 Toast</h2>
        <p>同時顯示多個通知：</p>
        <div class="button-group">
            <button class="demo-btn btn-info" onclick="demoMultiple()">
                🎯 顯示多個 Toast
            </button>
        </div>
    </div>

    <div class="demo-section">
        <h2>進階用法</h2>
        <p>使用 Toast 物件進行完整控制：</p>
        <div class="button-group">
            <button class="demo-btn btn-warning" onclick="demoCustomDuration()">
                ⏱️ 3 秒消失
            </button>
            <button class="demo-btn btn-info" onclick="demoNeverClose()">
                🔒 手動關閉
            </button>
        </div>
        <div class="code-block">
// 自定義持續時間（3秒）<br>
Toast.show('3秒後消失', 'warning', '快速訊息', 3000);<br>
<br>
// 需要手動關閉<br>
Toast.show('點擊 X 關閉', 'info', '重要訊息', 0);
        </div>
    </div>

    <div class="demo-section">
        <h2>Laravel Integration</h2>
        <p>測試 Laravel Session Flash Messages：</p>
        <div class="button-group">
            <a href="{{ route('toast.demo.flash', ['type' => 'success']) }}" class="demo-btn btn-success" style="text-decoration: none;">
                Test Success Flash
            </a>
            <a href="{{ route('toast.demo.flash', ['type' => 'error']) }}" class="demo-btn btn-error" style="text-decoration: none;">
                Test Error Flash
            </a>
        </div>
        <div class="code-block">
// Controller:<br>
return redirect()->back()<br>
    ->with('success', 'Operation completed!');
        </div>
    </div>
</div>

@push('scripts')
<script>
// Demo functions
function demoAddToCart() {
    successToast('商品已成功加入購物車！', '購物車');
    setTimeout(() => {
        infoToast('購物車商品數量：3 項');
    }, 500);
}

function demoPlaceOrder() {
    infoToast('正在處理您的訂單...', '處理中');
    setTimeout(() => {
        successToast('訂單 ORD202510070001 已成功建立！', '訂單確認');
    }, 1500);
}

function demoLoginFailed() {
    errorToast('電子郵件或密碼不正確', '登入失敗');
}

function demoLowStock() {
    warningToast('此商品庫存僅剩 3 件', '庫存警告');
}

function demoProcessing() {
    infoToast('您的請求正在處理中，請稍候...', '處理中');
}

function demoMultiple() {
    successToast('第一個通知');
    setTimeout(() => infoToast('第二個通知'), 300);
    setTimeout(() => warningToast('第三個通知'), 600);
    setTimeout(() => errorToast('第四個通知'), 900);
}

function demoCustomDuration() {
    Toast.show('這個訊息 3 秒後消失', 'warning', '快速訊息', 3000);
}

function demoNeverClose() {
    Toast.show('這個訊息不會自動消失，請點擊 X 關閉', 'info', '重要訊息', 0);
}
</script>
@endpush

@endsection
