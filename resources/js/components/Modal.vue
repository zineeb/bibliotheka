<template>
    <!-- Modal container, emitting 'close' event when clicking outside of the modal -->
    <div class="modal" @click.self="$emit('close')">
        <!-- Modal content container -->
        <div class="modal-content">
            <!-- Modal header, containing the slot for header content and close button -->
            <div class="modal-header">
                <slot name="header"></slot>
                <!-- <button @click="$emit('close')">&times;</button> -->
            </div>
            <!-- Modal body, containing the slot for body content -->
            <div class="modal-body">
                <slot name="body"></slot>
            </div>
            <!-- Modal footer, containing the slot for footer content -->
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