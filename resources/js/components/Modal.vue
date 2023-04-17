<template>
    <div class="modal" @click.self="$emit('close')">
        <div class="modal-content">
            <div class="modal-header">
                <slot name="header"></slot>
                <button @click="$emit('close')">&times;</button>
            </div>
            <div class="modal-body">
                <slot name="body"></slot>
            </div>
            <div class="modal-footer">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Modal",
};
</script>

<style scoped>
.modal {
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header button {
    font-size: 1.5em;
    border: none;
    background: none;
    cursor: pointer;
}

.modal-body {
    padding: 10px 0;
}

.modal-footer {
    text-align: right;
}
</style>
