<template>
    <el-dialog
        :title="titleDialog"
        :visible="showDialog"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        append-to-body
        @close="close"
        @open="create"
    >
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.item_id }"
                        >
                            <label class="control-label">Producto</label>
                            <el-select
                                v-model="form.item_id"
                                filterable
                                remote
                                :remote-method="searchRemoteItems"
                                :loading="loading_search"
                                @change="changeItem"
                            >
                                <el-option
                                    v-for="option in items"
                                    :key="option.id"
                                    :value="option.id"
                                    :label="option.description"
                                ></el-option>
                            </el-select>
                            <small
                                class="text-danger"
                                v-if="errors.item_id"
                                v-text="errors.item_id[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.quantity }"
                        >
                            <label class="control-label">Cantidad</label>
                            <el-input
                                class="w-100"
                                v-model="form.quantity"
                            ></el-input>
                            <small
                                class="text-danger"
                                v-if="errors.quantity"
                                v-text="errors.quantity[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.warehouse_id }"
                        >
                            <label class="control-label">Almacén</label>
                            <el-select
                                v-model="form.warehouse_id"
                                filterable
                                @change="changeItem"
                            >
                                <el-option
                                    v-for="option in warehouses"
                                    :key="option.id"
                                    :value="option.id"
                                    :label="option.description"
                                ></el-option>
                            </el-select>
                            <small
                                class="text-danger"
                                v-if="errors.warehouse_id"
                                v-text="errors.warehouse_id[0]"
                            ></small>
                            <span>
                                <div
                                    style="padding-top: 3%"
                                    class="col-md-6 col-sm-6"
                                    v-if="
                                        form.item_id &&
                                        form.lots_enabled &&
                                        form.warehouse_id
                                    "
                                >
                                    <a
                                        href="#"
                                        class="h5 text-center font-weight-bold text-info"
                                        @click.prevent="clickLotGroup"
                                        >[&#10004; Seleccionar lote]</a
                                    >
                                </div>
                                <div
                                    style="padding-top: 3%"
                                    class="col-md-6 col-sm-6"
                                    v-if="
                                        form.item_id &&
                                        form.series_enabled &&
                                        form.warehouse_id
                                    "
                                >
                                    <!-- <el-button type="primary" native-type="submit" icon="el-icon-check">Elegir serie</el-button> -->
                                    <a
                                        href="#"
                                        class="h5 text-center font-weight-bold text-info"
                                        @click.prevent="clickSelectLots"
                                        >[&#10004; Seleccionar series]</a
                                    >
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="references"
                                >Referencias</label
                            >
                            <el-select
                                v-model="form.inventory_reference_id"
                                filterable
                            >
                                <el-option
                                    v-for="option in references"
                                    :key="option.id"
                                    :value="option.id"
                                    :label="option.description"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>

                    <!--<div class="col-md-3" v-show="form.lots_enabled">
                        <div class="form-group" :class="{'has-danger': errors.date_of_due}">
                            <label class="control-label">Fec. Vencimiento</label>
                            <el-date-picker v-model="form.date_of_due" type="date" value-format="yyyy-MM-dd" :clearable="true"></el-date-picker>
                            <small class="text-danger" v-if="errors.date_of_due" v-text="errors.date_of_due[0]"></small>
                        </div>
                    </div> -->

                    <!--<div style="padding-top: 3%" class="col-md-4" v-if="form.warehouse_id && form.series_enabled">

                        <a href="#"  class="text-center font-weight-bold text-info" @click.prevent="clickLotcode">[&#10004; Ingresar series]</a>
                    </div>  -->
                    <div class="col-md-8">
                        <div
                            class="form-group"
                            :class="{
                                'has-danger': errors.inventory_transaction_id,
                            }"
                        >
                            <label class="control-label">Motivo traslado</label>
                            <el-select
                                v-model="form.inventory_transaction_id"
                                filterable
                            >
                                <el-option
                                    v-for="option in inventory_transactions"
                                    :key="option.id"
                                    :value="option.id"
                                    :label="option.name"
                                ></el-option>
                            </el-select>
                            <small
                                class="text-danger"
                                v-if="errors.inventory_transaction_id"
                                v-text="errors.inventory_transaction_id[0]"
                            ></small>
                        </div>
                    </div>
                    <div
                        class="col-md-4"
                        v-if="
                            form.has_sizes && form.warehouse_id && form.item_id
                        "
                    >
                        <a
                            href="#"
                            class="text-center font-weight-bold text-info"
                            @click.prevent="clickSizes"
                            >[&#10004; Ingresar Tallas]</a
                        >
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Fecha registro</label>
                        <el-date-picker
                            v-model="form.created_at"
                            type="datetime"
                            value-format="yyyy-MM-dd HH:mm:ss"
                            format="dd/MM/yyyy HH:mm:ss"
                            :clearable="true"
                        ></el-date-picker>
                    </div>
                    <div class="col-md-8">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.comments }"
                        >
                            <label class="control-label">Comentarios </label>
                            <el-input
                                type="textarea"
                                :rows="3"
                                :maxlength="250"
                                v-model="form.comments"
                            ></el-input>
                            <small
                                class="text-danger"
                                v-if="errors.comments"
                                v-text="errors.comments[0]"
                            ></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-end mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button
                    type="primary"
                    native-type="submit"
                    :loading="loading_submit"
                    >Aceptar</el-button
                >
            </div>
        </form>

        <lots-group
            :quantity="form.quantity"
            :showDialog.sync="showDialogLots"
            :lots-group-all="lotsGroupAll"
            :lots_group="form.lots_group"
            @addRowLotGroup="addRowLotGroup"
        >
        </lots-group>

        <select-lots-form
            :showDialog.sync="showDialogSelectLots"
            :lots-all="lotsAll"
            :itemId="form.item_id"
            :lots="form.lots"
            :quantity="form.quantity"
            :warehouseId="form.warehouse_id"
            @addRowSelectLot="addRowSelectLot"
        >
        </select-lots-form>
        <form-size
            :showDialog.sync="showDialogSizes"
            :sizes="form.sizes"
            @addRowSize="addRowSize"
        >
        </form-size>
    </el-dialog>
</template>

<script>
//  import InputLotsForm from '../../../../../../resources/js/views/tenant/items/partials/lots.vue'
// import OutputLotsForm from './partials/lots.vue'
//import LotsGroup from './lots_group.vue'
import LotsGroup from "../../../../../../resources/js/views/tenant/documents/partials/lots_group.vue";
import SelectLotsForm from "../../../../../../resources/js/views/tenant/documents/partials/lots.vue";
import FormSize from "./form_size.vue";
//import SelectLotsForm from './lots.vue'
import { filterWords } from "../../../../../../resources/js/helpers/functions";

export default {
    components: { LotsGroup, SelectLotsForm, FormSize },
    props: ["showDialog", "recordId"],
    data() {
        return {
            type: "output",
            loading: false,
            loading_search: false,
            loading_submit: false,
            showDialogLots: false,
            showDialogSizes: false,
            showDialogSelectLots: false,
            titleDialog: null,
            resource: "inventory",
            errors: {},
            form: {},
            items: [],
            warehouses: [],
            inventory_transactions: [],
            lotsAll: [],
            lotsGroupAll: [],
            references: [],
        };
    },
    created() {
        this.initForm();
    },
    methods: {
        addRowSize(sizes) {
            this.form.sizes = sizes;
            this.form.quantity = sizes.reduce(
                (a, b) => a + Number(b["quantity"] || 0),
                0
            );
        },
        clickSizes() {
            this.showDialogSizes = true;
        },
        async changeItem() {
            this.form.lots = [];
            let item = await _.find(this.items, { id: this.form.item_id });
            this.form.lots_enabled = item.lots_enabled;
            this.lotsAll = await _.filter(item.lots, {
                warehouse_id: this.form.warehouse_id,
            });
            // console.log(item)
            // this.form.lots = lots
            this.form.lots_enabled = item.lots_enabled;
            this.form.series_enabled = item.series_enabled;
            this.form.lots_group = [];

            this.lotsGroupAll = item.lots_group;
            this.form.has_sizes = item.has_sizes;
            this.form.all_sizes = item.sizes;

            this.filterSizes();
        },
        // addRowOutputLot(lots) {
        //     this.form.lots = lots
        // },
        // addRowLot(lots) {
        //     this.form.lots = lots
        // },
        clickLotcode() {
            this.showDialogLots = true;
        },
        clickLotcodeOutput() {
            this.showDialogLotsOutput = true;
        },
        filterSizes() {
            if (this.form.warehouse_id) {
                this.form.sizes = this.form.all_sizes
                    .filter((x) => x.warehouse_id == this.form.warehouse_id)
                    .map((x) => {
                        return {
                            ...x,
                            quantity: 0,
                        };
                    });
            }
        },
        initForm() {
            this.errors = {};
            this.form = {
                id: null,
                item_id: null,
                warehouse_id: null,
                inventory_transaction_id: null,
                quantity: 0,
                type: this.type,
                lot_code: null,
                lots_enabled: false,
                series_enabled: false,
                lots: [],
                date_of_due: null,
                IdLoteSelected: null,
                lots_group: [],
                created_at: null,
                inventory_reference_id: null,
                comments: null,
            };
        },
        async initTables() {
            await this.$http
                .get(`/${this.resource}/tables/transaction/${this.type}`)
                .then((response) => {
                    // this.items = response.data.items
                    this.references = response.data.references;
                    this.warehouses = response.data.warehouses;
                    this.inventory_transactions =
                        response.data.inventory_transactions;
                });
            await this.searchRemoteItems("");
        },
        async create() {
            this.loading = true;
            this.titleDialog = "Salida de producto del almacéna";
            await this.initTables();
            this.initForm();
            this.loading = false;
        },
        // async create() {
        //     this.titleDialog = 'Salida de producto del almacén'
        //     await this.$http.get(`/${this.resource}/tables/transaction/output`)
        //         .then(response => {
        //             // this.items = response.data.items
        //             this.warehouses = response.data.warehouses
        //             this.inventory_transactions = response.data.inventory_transactions
        //         })
        //
        // },
        async searchRemoteItems(search) {
            this.loading_search = true;
            this.items = [];
            await this.$http
                .post(`/${this.resource}/search_items`, { search: search })
                .then((response) => {
                    let items = response.data.items;
                    if (items.length > 0) {
                        this.items = items; //filterWords(search, items);
                    }
                });
            this.loading_search = false;
        },
        async submit() {
            if (this.form.lots.length > 0 && this.form.series_enabled) {
                //let select_lots = await _.filter(this.form.lots, {'has_sale': true})
                if (this.form.lots.length !== parseInt(this.form.quantity)) {
                    return this.$message.error(
                        "La cantidad ingresada es diferente a las series seleccionadas"
                    );
                }
            }
            if (this.form.lots_enabled) {
                if (!this.form.IdLoteSelected)
                    return this.$message.error("Debe seleccionar un lote.");
            }

            // let _lots_group = [];
            // _.forEach(this.form.lots_group, row => {
            //     _lots_group.push({
            //         'checked': row.checked,
            //         'code': row.code,
            //         'date_of_due': row.date_of_due,
            //         'id': row.id,
            //         'quantity': row.quantity,
            //     })
            // })
            //this.form.lots_group = _.head(this.form.lots_group);
            this.loading_submit = true;
            this.form.type = this.type;
            // console.log(this.form)
            await this.$http
                .post(`/${this.resource}/transaction`, this.form)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                        this.close();
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                        // console.log(error.response.data)
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
        close() {
            this.$emit("update:showDialog", false);
            this.initForm();
        },
        clickLotGroup() {
            this.showDialogLots = true;
        },
        addRowLotGroup(id) {
            console.log(id);
            this.form.IdLoteSelected = id;
        },
        async clickSelectLots() {
            this.showDialogSelectLots = true;
        },
        addRowSelectLot(lots) {
            console.log(lots);
            this.form.lots = lots;
        },
    },
};
</script>
