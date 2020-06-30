<template>
    <div class="modal fade" tabindex="-1" role="dialog" v-show="this.show" @click="this.close">
        <div class="modal-dialog" role="document" @click.stop>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button type="button" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot/>            
                </div>
                <div v-if="hasFooter" class="modal-footer">
                    <slot name="footer" />
                    <button v-if="closeButton" type="button" @click="close">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Modal from 'bootstrap/js/src/modal';
    
    export default {
        name: 'VModal',
                
        props: {            
            loading: {
                type: Boolean,
                default: false
            },
            closeButton: {
                type: Boolean,
                default: true
            },
            hasFooter: {
                type: Boolean,
                default: true
            },
            title: {
                type: String
            },
            show: {
                type: Boolean,
                default: false
            }
        },

        watch: {
            // Watch for a change in show, so we can call for open or close
            show(value) {
                if (value) {
                    this.open()
                } else if (!value) {
                    this.close()
                }
            }
        },

        beforeDestroy() {
            document.removeEventListener('keydown', this.handleKeydown);
            if (this.isDef(this.modalInstance)) {
                this.modalInstance.dispose();
                this.modalInstance = null;
            }
        },


        mounted() {
            this.modalInstance = new Modal(this.$el);
            
            // If the esc button is typed, close modal
            document.addEventListener('keydown', this.handleKeydown);
        },
        data() {
            return {
                modalInstance: null,
            };
        },
        
        methods: {
            handleKeydown(e) {
                if (this.show && e.keyCode === 27) {
                    this.close();
                }
            },
            close() {
                if (this.isDef(this.modalInstance)) {
                    this.modalInstance.hide();
                }
                
                this.$emit('close');
            },
            open() {
                this.modalInstance.show();
            },
            isDef(obj) {
                return typeof obj !== undefined && obj !== null;
            }
        }
    }
</script>
