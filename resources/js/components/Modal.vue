<template>
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot/>            
                </div>
                <div v-if="hasFooter" class="modal-footer">
                    <slot name="footer" />
                    <button v-if="closeButton" type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Modal',
                
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
            }
        }
    }
</script>

