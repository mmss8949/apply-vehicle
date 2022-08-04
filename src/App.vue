<template>
  <v-app>
    <v-main class="pa-3">
      <!--overlay-->
      <v-overlay :value="overlay">
        <v-progress-circular
          indeterminate
          size="64"
        ></v-progress-circular>
      </v-overlay>
      <v-row
        no-gutters
        justify="end"
      >
        <v-btn
          class="ma-2"
          outlined
          rounded
          :color="themeColor"
          @click="newVehicleData()"
        >
          <v-icon left>
            mdi-plus
          </v-icon>
          新增行照
        </v-btn>
      </v-row>
      <v-tabs
        v-model="tab"
        grow
        :color="themeColor"
      >
        <v-tab>
          審核完成
        </v-tab>
        <v-tab>
          處理中
        </v-tab>

      </v-tabs>

      <v-tabs-items v-model="tab">
        <!--completed-->
        <v-tab-item>
          <!--if data is empty-->
          <v-alert
            v-if="completedData.length==0"
            type="info"
            text
          >無資料</v-alert>
          <!--data-->
          <v-hover
            v-slot="{ hover }"
            v-for="(item) in completedData"
            :key="item.app_no"
          >
            <v-row
              no-gutters
              class="row-border hover-bd-l-color py-lg-4 pr-lg-0 py-md-2 pr-md-0 py-5 pr-2 pl-2"
            >
              <!-- plate number-->
              <v-col
                lg="3"
                md="3"
                cols="12"
              >
                <div :style="{'font-size':'30px','height':'40px','align-items':'end','font-weight':hover?'bold':'normal'}"> {{item.plate_number1}}</div>
              </v-col>
              <!--state-->
              <v-col
                lg="1"
                md="1"
                cols="4"
              >
                <div
                  style="font-size: 23px;height:40px;align-items:end;"
                  class="d-flex pa-0 pr-3"
                > {{changeCategoryToString(item.vehicle_category) }}</div>
              </v-col>
              <v-col
                lg="2"
                md="2"
                cols="8"
              >
                <div
                  style="font-size: 23px;height:40px;align-items:end;"
                  class="d-flex pa-0 pr-3"
                > {{changeWhosToString(item.whos_vehicle) }}</div>
              </v-col>
              <v-col
                lg="2"
                md="2"
                cols="12"
              >
                <div
                  style="font-size: 23px;height:40px;align-items:end;"
                  class="d-flex pa-0 pr-3"
                > {{item.approva_date}}審畢</div>
              </v-col>
              <!--action-->
              <v-col
                lg="4"
                md="4"
                cols="12"
                class="d-flex justify-end"
              >
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon
                      size="35"
                      class="mr-7"
                      v-bind="attrs"
                      v-on="on"
                      :color="hover?'black':'grey'"
                      @click="deleteVehicleData(item)"
                      style="cursor:pointer"
                    >mdi-delete-outline </v-icon>
                  </template>
                  <span>刪除</span>
                </v-tooltip>
              </v-col>
            </v-row>
          </v-hover>
        </v-tab-item>
        <!--not completed-->
        <v-tab-item>
          <!--if data is empty-->
          <v-alert
            v-if="notCompletedData.length==0"
            type="info"
            text
          >無資料</v-alert>
          <!--data-->
          <template v-else>
            <v-hover
              v-slot="{ hover }"
              v-for="(item) in notCompletedData"
              :key="item.app_no"
            >
              <v-row
                no-gutters
                class="row-border hover-bd-l-color py-lg-4 pr-lg-0 py-md-2 pr-md-0 py-5 pr-2 pl-2"
              >
                <!-- plate number-->
                <v-col
                  lg="3"
                  md="3"
                  cols="12"
                >
                  <div :style="{'font-size':'30px','height':'40px','align-items':'end','font-weight':hover?'bold':'normal'}"> {{item.plate_number1}}</div>
                </v-col>
                <!--state-->
                <v-col
                  lg="1"
                  md="1"
                  cols="4"
                >
                  <div
                    style="font-size: 23px;height:40px;align-items:end;"
                    class="d-flex pa-0 pr-3"
                  > {{changeCategoryToString(item.vehicle_category) }}</div>
                </v-col>
                <v-col
                  lg="2"
                  md="2"
                  cols="8"
                >
                  <div
                    style="font-size: 23px;height:40px;align-items:end;"
                    class="d-flex pa-0 pr-3"
                  > {{changeWhosToString(item.whos_vehicle) }}</div>
                </v-col>
                <v-col
                  lg="2"
                  md="2"
                  cols="12"
                >
                  <div
                    style="font-size: 23px;height:40px;align-items:end;"
                    class="d-flex pa-0 pr-3"
                  > {{changeStatusToString(item.approval_status) }}</div>
                </v-col>
                <!--action-->
                <v-col
                  lg="4"
                  md="4"
                  cols="12"
                  class="d-flex justify-end"
                >

                  <!--reason-->
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        size="35"
                        class="mr-7"
                        v-bind="attrs"
                        v-on="on"
                        :color="hover
                  ?item.approval_status=='2'  && item.check_reason && item.check_reason.length!=0
                    ?'black'
                    :'grey'
                  :'grey'"
                        @click="item.approval_status=='2'  && item.check_reason && item.check_reason.length!=0
                  ?showDialogCheckList(item)
                  :()=>{}"
                        :style="item.approval_status=='2' &&item.check_reason && item.check_reason.length!=0
                  ?'cursor:pointer'
                  :'cursor:not-allowed'"
                      >mdi-alert-circle-outline </v-icon>
                    </template>
                    <span>退件原因</span>
                  </v-tooltip>

                  <!--view-->
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        size="35"
                        class="mr-6"
                        v-bind="attrs"
                        v-on="on"
                        :color="hover
                  ?'black'
                  :'grey'"
                        @click="viewVehicleData(item)"
                        style="cursor:pointer"
                      >mdi-eye-outline </v-icon>
                    </template>
                    <span>查看</span>
                  </v-tooltip>

                  <!--delete-->
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        size="35"
                        class="mr-7"
                        v-bind="attrs"
                        v-on="on"
                        :color="hover
                  ?item.approval_status=='1' ||item.approval_status=='2'
                    ?'black'
                    :'grey'
                  :'grey'"
                        @click="item.approval_status=='1' ||item.approval_status=='2'
                  ?deleteVehicleData(item)
                  :()=>{}"
                        :style="item.approval_status=='1' ||item.approval_status=='2'
                  ?'cursor:pointer'
                  :'cursor:not-allowed'"
                      >mdi-delete-outline </v-icon>
                    </template>
                    <span>刪除</span>
                  </v-tooltip>
                </v-col>
              </v-row>
            </v-hover>
          </template>
        </v-tab-item>

      </v-tabs-items>

      <!--dialog-->
      <v-dialog
        v-model="dialog.show"
        max-width="600"
        persistent
      >
        <!--insert edit-->
        <v-card v-if="dialog.index==0">
          <v-card-title class="text-h5">
            {{dialog.readonly?'查看行照相關資料':'請新增行照相關資料'}}
          </v-card-title>

          <v-card-text class="pa-5 pb-0">
            <!--show img-->
            <v-row
              no-gutters
              class="pb-3"
              justify="end"
              v-show="dialog.readonly"
            >
              <!--relationship-->
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-hover v-slot="{ hover }">
                    <v-icon
                      size="30"
                      class="mr-3"
                      v-bind="attrs"
                      v-on="on"
                      :color="hover
                  ?dialog.field.relationship_Img && dialog.field.relationship_Img.length!=0
                    ?'black'
                    :'grey'
                  :'grey'"
                      @click="dialog.field.relationship_Img && dialog.field.relationship_Img.length!=0
                  ?showDialogImg(dialog.field.relationship_Img)
                  :()=>{}"
                      :style="dialog.field.relationship_Img && dialog.field.relationship_Img.length!=0
                  ?'cursor:pointer'
                  :'cursor:not-allowed'"
                    >mdi-file-outline </v-icon>
                  </v-hover>
                </template>
                <span>關係證明</span>
              </v-tooltip>

              <!--license-->
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-hover v-slot="{ hover }">
                    <v-icon
                      size="30"
                      v-bind="attrs"
                      v-on="on"
                      :color="hover
                  ?dialog.field.vehicle_Img && dialog.field.vehicle_Img.length!=0
                    ?'black'
                    :'grey'
                  :'grey'"
                      @click="dialog.field.vehicle_Img && dialog.field.vehicle_Img.length!=0
                  ?showDialogImg(dialog.field.vehicle_Img)
                  :()=>{}"
                      :style="dialog.field.vehicle_Img && dialog.field.vehicle_Img.length!=0
                  ?'cursor:pointer'
                  :'cursor:not-allowed'"
                    >mdi-card-account-details-outline </v-icon>
                  </v-hover>

                </template>
                <span>行照</span>
              </v-tooltip>
            </v-row>
            <v-form
              ref="form"
              v-model="valid"
              lazy-validation
              :readonly="dialog.readonly"
            >
              <v-row
                no-gutters
                justify="space-between"
              >
                <v-col cols="5">
                  <v-text-field
                    outlined
                    dense
                    label="*車牌號碼"
                    placeholder="請勿輸入 - ex. 123ABC"
                    v-model="dialog.field.plate_number"
                    :rules="[dialog.rules.required, dialog.rules.symbol,dialog.rules.upAndNumber]"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="5">
                  <v-select
                    :items="vehicle_category"
                    item-text="code_value"
                    item-value="code_no"
                    label="*行照種類"
                    dense
                    outlined
                    v-model="dialog.field.vehicle_category"
                  ></v-select>
                </v-col>
              </v-row>

              <v-row
                no-gutters
                justify="space-between"
              >
                <v-col cols="5">
                  <v-text-field
                    outlined
                    dense
                    label="*車主"
                    v-model="dialog.field.car_owner"
                    :rules="[dialog.rules.required, dialog.rules.symbol]"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="5">
                  <v-text-field
                    outlined
                    dense
                    label="*廠牌"
                    placeholder="ex. YAMMAHA"
                    v-model="dialog.field.brand"
                    :rules="[dialog.rules.required, dialog.rules.symbol]"
                  >
                  </v-text-field>
                </v-col>

              </v-row>

              <v-row
                no-gutters
                justify="space-between"
              >
                <v-col cols="5">
                  <v-text-field
                    outlined
                    dense
                    label="*車色"
                    placeholder="ex. 藍色"
                    v-model="dialog.field.color"
                    :rules="[dialog.rules.required, dialog.rules.symbol]"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="5">
                  <v-text-field
                    outlined
                    dense
                    label="*排氣量"
                    placeholder="ex. 125"
                    v-model="dialog.field.cc"
                    :rules="[dialog.rules.required, dialog.rules.symbol]"
                  > </v-text-field>
                </v-col>
              </v-row>

              <v-row
                no-gutters
                v-if="!dialog.readonly"
              >
                <v-file-input
                  chips
                  dense
                  outlined
                  append-outer-icon="mdi-file-find "
                  prepend-icon
                  prepend-inner-icon="mdi-paperclip "
                  label="*上傳行照照片"
                  v-model="dialog.field.vehicle_file"
                  :rules="[dialog.rules.required]"
                  @click:append-outer="dialog.field.vehicle_file
                    ?openImg(dialog.field.vehicle_file)
                    :()=>{}"
                ></v-file-input>
              </v-row>

              <v-row
                no-gutters
                justify="space-between"
              >
                <v-select
                  :items="whos_vehicle"
                  item-text="code_value"
                  item-value="code_no"
                  label="*誰的車子"
                  dense
                  outlined
                  v-model="dialog.field.whos_vehicle"
                ></v-select>
              </v-row>
              <template v-if="dialog.field.whos_vehicle==2">
                <v-row
                  no-gutters
                  justify="space-between"
                >
                  <v-col cols="12">
                    <v-text-field
                      outlined
                      dense
                      label="*與車主的關係"
                      v-model="dialog.field.relationship_owner"
                      :rules="[dialog.rules.required, dialog.rules.symbol]"
                    >
                    </v-text-field>
                  </v-col>
                </v-row>

                <v-row
                  no-gutters
                  v-if="!dialog.readonly"
                >
                  <v-file-input
                    chips
                    outlined
                    dense
                    append-outer-icon="mdi-file-find "
                    prepend-icon
                    prepend-inner-icon="mdi-paperclip "
                    label="*上傳與車主關係證明之照片"
                    placeholder="ex. 父子"
                    v-model="dialog.field.relationship_file"
                    :rules="[dialog.rules.required]"
                    @click:append-outer="dialog.field.relationship_file
                    ?openImg(dialog.field.relationship_file)
                    :()=>{}"
                  ></v-file-input>
                </v-row>
              </template>
            </v-form>
          </v-card-text>

          <v-card-actions v-if="!dialog.readonly">
            <v-spacer></v-spacer>

            <v-btn
              color="red"
              text
              @click="controllDialog(false)"
            >
              取消
            </v-btn>
            <v-btn
              color="indigo"
              text
              @click="postVehicleData(3)"
            >
              送審
            </v-btn>
          </v-card-actions>
          <v-card-actions v-else>
            <v-spacer></v-spacer>
            <v-btn
              color="red"
              text
              @click="controllDialog(false)"
            >
              關閉
            </v-btn>
          </v-card-actions>
        </v-card>
        <!-- img-->
        <v-card v-else-if="dialog.index==1">'
          <v-card-title class="text-h5">
            <v-icon
              size="40"
              @click="controllIndexDialog(0)"
            >mdi-arrow-left-thin </v-icon>照片
          </v-card-title>
          <v-card-text>
            <v-img :src="dialog.img"></v-img>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="indigo"
              text
              @click="controllDialog(false)"
            >
              關閉
            </v-btn>
          </v-card-actions>
        </v-card>
        <!--delete-->
        <v-card v-else-if="dialog.index==2">
          <v-card-title>
            確定是否刪除?
          </v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="indigo"
              text
              @click="controllDialog(false)"
            >
              取消
            </v-btn>
            <v-btn
              color="red"
              text
              @click="postVehicleData(4)"
            >
              確定
            </v-btn>
          </v-card-actions>
        </v-card>
        <!--reason-->
        <v-card v-else-if="dialog.index==3">
          <v-card-title>
            退件原因
          </v-card-title>

          <v-list-item
            three-line
            v-for="(item,index) in dialog.check_reason"
            :key="item+index"
          >
            <v-list-item-content>
              <v-list-item-title>{{item.check_reason}}</v-list-item-title>
              <v-list-item-subtitle>
                {{item.contact_info}}
              </v-list-item-subtitle>
              <v-list-item-subtitle>
                {{item.check_date}}
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="red"
              text
              @click="controllDialog(false)"
            >
              取消
            </v-btn>
            <v-btn
              color="indigo"
              text
              @click="editVehicleData()"
            >
              編輯
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-main>
  </v-app>
</template>

<script>
import axios from "axios";

export default {
  name: "App",

  components: {},

  data: () => ({
    test:"30px",
    testMode: false,
    target: `gGroups_i=${gGroups}&gSys_s=${gSys_s}&gFunc_s=${gFunc_s}`,
    themeColor: "",
    overlay: false,
    tab: 0,
    valid: true,
    licenseData: [],
    completedData: [],
    notCompletedData: [],
    whos_vehicle: [],
    vehicle_category: [],
    approval_status: [],
    upload_status: [],
    dialog: {
      index: 0,
      show: false,
      img: "",
      status: "",
      readonly: false,
      check_reason: [],
      field: {
        currentSelector: null,
        plate_number: "",
        vehicle_category: "",
        car_owner: "",
        brand: "",
        color: "",
        cc: "",
        vehicle_file: null,
        vehicle_Img: "",
        whos_vehicle: "",
        relationship_owner: "",
        relationship_file: null,
        relationship_Img: "",
      },
      rules: {
        required: (value) => !!value || "Required.",
        symbol: (value) =>
          !/[`~!@#$%^&*()_+<>?:"{},.\/;'[\]]/im.test(value) || "請不要輸入符號",
        upAndNumber: (value) => /^[A-Z\d]*$/.test(value) || "請輸入大寫與數字",
      },
    },
  }),
  created() {
    const self = this;
    if (location.href.indexOf("localhost") != -1) self.testMode = true;
    else self.testMode = false;
  },
  mounted() {
    const self = this;
    var getColor = () => {
      //text to hex
      var getHex = function (text) {
        var hex;
        switch (text) {
          case "blue":
            hex = "#3b5998";
            break;
          case "green":
            hex = "#098c6e";
            break;
          case "red":
            hex = "#b72e4a";
            break;
          case "highway":
            hex = "#c60";
            break;
          case "violet":
            hex = "#8b00ff";
            break;
        }
        return hex;
      };
      //conditional
      if (window.localStorage.getItem("_client_color")) {
        self.themeColor = getHex(window.localStorage.getItem("_client_color"));
      } else {
        self.themeColor = getHex(window.localStorage.getItem("_default_color"));
      }
    };
    //first call
    getColor();

    //add listener to local storage
    window.addEventListener("storage", () => {
      getColor();
    });

    //main porcess
    self.mainProcess();
  },
  methods: {
    mainProcess: async function () {
      const self = this;
      try {
        var vehicleInfo;
        self.overlay = true;
        vehicleInfo = await self.getVehicleInfo();
        self.setVehicleInfo(vehicleInfo);
        self.licenseData = await self.getVehicleData();
        self.sortVehicleData(self.licenseData);
        self.overlay = false;
      } catch (error) {
        throw new Error(error);
      }
    },
    controllDialog: function (state) {
      const self = this;
      self.dialog.show = state;
    },
    controllIndexDialog: function (number) {
      const self = this;
      self.dialog.index = number;
    },
    checkDialogRules: function () {
      const self = this;
      var ruleRequired = self.dialog.rules.required;
      var ruleSymbol = self.dialog.rules.symbol;
      var ruleUper = self.dialog.rules.upAndNumber;
      var field = self.dialog.field;
      var answerState = true;
      var requiredState = false;
      var symbolState = false;
      var uperState = false;

      //required check
      requiredState =
        ruleRequired(field.plate_number) == true &&
        ruleRequired(field.car_owner) == true &&
        ruleRequired(field.brand) == true &&
        ruleRequired(field.color) == true &&
        ruleRequired(field.cc) == true &&
        ruleRequired(field.vehicle_file) == true;
      if (field.whos_vehicle != 1)
        requiredState = ruleRequired(field.whos_vehicle) == true;

      //symbol check
      symbolState =
        ruleSymbol(field.plate_number) == true &&
        ruleSymbol(field.car_owner) == true &&
        ruleSymbol(field.brand) == true &&
        ruleSymbol(field.color) == true &&
        ruleSymbol(field.cc) == true;
      if (field.whos_vehicle != 1)
        symbolState = ruleSymbol(field.whos_vehicle) == true;

      //upercase check
      uperState = ruleUper(field.plate_number) == true;

      answerState = requiredState && symbolState && uperState;

      return answerState;
    },

    getDialog: function () {
      const self = this;
      let field = self.dialog.field;
      let item = new FormData();

      item.append("app_no", self.dialog.currentSelector);
      item.append("plate_number1", field.plate_number);
      item.append("vehicle_category", field.vehicle_category);
      item.append("car_owner", field.car_owner);
      item.append("brand", field.brand);
      item.append("color", field.color);
      item.append("cc", field.cc);
      item.append("vehicle_file", field.vehicle_file);
      item.append("whos_vehicle", field.whos_vehicle);
      item.append("relationship_owner", field.relationship_owner);
      item.append("relationship_file", field.relationship_file);
      return item;
    },
    setDialog: function (item) {
      const self = this;
      var field = self.dialog.field;
      self.dialog.status = item.approval_status;
      self.dialog.check_reason = item.check_reason;

      field.plate_number = item.plate_number1;
      field.vehicle_category = parseInt(item.vehicle_category);
      field.car_owner = item.car_owner;
      field.brand = item.brand;
      field.color = item.color;
      field.cc = item.cc;
      field.vehicle_Img = item.vehicle_license_img;
      field.whos_vehicle = parseInt(item.whos_vehicle);
      field.relationship_owner = item.relationship_owner;
      field.relationship_Img = item.relationship_img;

      self.controllDialog(true);
    },
    clearDialog: function () {
      const self = this;
      self.dialog.currentSelector = null;
      self.dialog.check_reason = [];
      self.dialog.status = "";
      self.dialog.readonly = false;

      var field = self.dialog.field;
      field.plate_number = "";
      field.vehicle_category = "";
      field.car_owner = "";
      field.brand = "";
      field.color = "";
      field.cc = "";
      field.vehicle_file = null;
      field.whos_vehicle = "";
      field.relationship_owner = "";
      field.relationship_file = null;
      field.relationship_Img = "";
      field.vehicle_Img = "";
    },
    /**
     * change text
     */
    changeWhosToString: function (number) {
      const self = this;
      var index;
      var text = "";

      index = self.whos_vehicle.findIndex(
        (item) => item.code_no == parseInt(number)
      );
      text = self.whos_vehicle[index].code_value;
      return text;
    },
    changeCategoryToString: function (number) {
      const self = this;
      var index;
      var text = "";

      index = self.vehicle_category.findIndex(
        (item) => item.code_no == parseInt(number)
      );
      text = self.vehicle_category[index].code_value;
      return text;
    },
    changeStatusToString: function (number) {
      const self = this;
      var index;
      var text = "";

      index = self.approval_status.findIndex(
        (item) => item.code_no == parseInt(number)
      );
      text = self.approval_status[index].code_value;
      return text;
    },

    /**
     * UI dialog controll
     */
    newVehicleData: function () {
      const self = this;
      self.clearDialog();
      self.dialog.currentSelector = null;
      self.controllIndexDialog(0);
      self.controllDialog(true);
    },
    viewVehicleData: function (item) {
      const self = this;
      self.clearDialog();
      self.setDialog(item);
      self.dialog.readonly = true;
      self.controllIndexDialog(0);
      self.controllDialog(true);
    },
    editVehicleData: function () {
      const self = this;
      self.controllIndexDialog(0);
    },
    deleteVehicleData: function (item) {
      const self = this;
      self.dialog.currentSelector = item.app_no;
      self.controllIndexDialog(2);
      self.controllDialog(true);
    },
    showDialogImg: function (img) {
      const self = this;
      self.dialog.img = img;
      self.controllIndexDialog(1);
      self.controllDialog(true);
    },
    showDialogCheckList: function (item) {
      const self = this;
      self.clearDialog();
      self.dialog.currentSelector = item.app_no;
      self.setDialog(item);
      self.controllIndexDialog(3);
      self.controllDialog(true);
    },
    /**
     * Data process
     */
    getVehicleData: function () {
      const self = this;
      return new Promise((resovle, reject) => {
        if (self.testMode)
          axios
            .get(
              "./vehicle_license/tools/json/vehicle_license.json?" +
                Math.random()
            )
            .then((respones) => {
              resovle(respones.data.data);
            })
            .catch((e) => {
              parent.sysDisplayMessage(
                "",
                -1,
                "",
                "",
                null,
                "取得資訊失敗:-102"
              );
              reject(e);
            });
        else
          axios
            .get("./kernel/vehicle_license_list_oci.php?" + self.target)
            .then((respones) => {
              resovle(respones.data.data);
            })
            .catch((e) => {
              parent.sysDisplayMessage(
                "",
                -1,
                "",
                "",
                null,
                "取得資訊失敗:-102"
              );
              reject(e);
            });
      });
    },
    sortVehicleData: function (data) {
      const self = this;
      data.forEach((item) => {
        if (parseInt(item.approval_status) < 4)
          self.notCompletedData.push(item);
        else self.completedData.push(item);
      });
    },
    postVehicleData: function (mode) {
      const self = this;
      self.overlay = true;
      var item;

      //tmp
      if (mode == 1) {
        var checkState = self.checkDialogRules();
        item = self.getDialog();
        item.append("todo", 1);

        if (self.testMode) {
          if (!checkState) alert("field can't be empty or symbol");
          else console.log(item);
          self.overlay = false;
        } else {
          if (checkState)
            axios
              .post(
                "./kernel/vehicle_license_edit_save.php?" + self.target,
                item
              )
              .then(async (respones) => {
                if (respones.data.result == 1) {
                  self.licenseData = await self.getVehicleData();
                  self.clearCompletedData();
                  self.sortVehicleData(self.licenseData);
                  parent.sysDisplayMessage("", 0, "", "", null, "暫存成功");
                } else {
                  parent.sysDisplayMessage(
                    "",
                    -1,
                    "",
                    "",
                    null,
                    "暫存失敗: " + respones.data.msg
                  );
                }
                self.overlay = false;
              })
              .catch((e) => console.log(e));
          else {
            parent.sysDisplayMessage("", -1, "", "", null, "欄位有誤");
            self.overlay = false;
          }
        }
      }

      // save
      else if (mode == 3) {
        var checkState = self.checkDialogRules();
        item = self.getDialog();
        item.append("todo", 3);

        if (self.testMode) {
          if (checkState) console.log(item);
          else alert("field can't be empty or symbol");
          self.overlay = false;
        } else {
          if (checkState) {
            axios
              .post(
                "./kernel/vehicle_license_edit_save.php?" + self.target,
                item
              )
              .then(async (respones) => {
                if (respones.data.result == 1) {
                  self.licenseData = await self.getVehicleData();
                  self.clearCompletedData();
                  self.sortVehicleData(self.licenseData);
                  parent.sysDisplayMessage("", 0, "", "", null, "新增成功");
                  self.controllDialog(false);
                } else {
                  parent.sysDisplayMessage(
                    "",
                    -1,
                    "",
                    "",
                    null,
                    "新增失敗: " + respones.data.msg
                  );
                }
                self.overlay = false;
              })
              .catch((e) => console.log(e));
          } else {
            parent.sysDisplayMessage("", -1, "", "", null, "欄位有誤");
            self.overlay = false;
          }
        }
      }

      //delete
      else if (mode == 4) {
        item = {};
        item.todo = 4;
        item.app_no = self.dialog.currentSelector;

        if (self.testMode) {
          console.log(item);
          self.overlay = false;
        } else {
          axios
            .post("./kernel/vehicle_license_list_save.php?" + self.target, item)
            .then(async (respones) => {
              if (respones.data.result == 1) {
                self.licenseData = await self.getVehicleData();
                self.clearCompletedData();
                self.sortVehicleData(self.licenseData);
                parent.sysDisplayMessage("", 0, "", "", null, "刪除成功");
              } else {
                parent.sysDisplayMessage(
                  "",
                  -1,
                  "",
                  "",
                  null,
                  "刪除失敗: " + respones.data.msg
                );
              }
              self.overlay = false;
            })
            .catch((e) => console.log(e));
        }
        self.controllDialog(false);
      }
    },
    getVehicleInfo: function () {
      const self = this;
      return new Promise((resovle, reject) => {
        if (self.testMode)
          axios
            .get(
              "./vehicle_license/tools/json/vehicle_license_tw.json?" +
                Math.random()
            )
            .then((respones) => {
              resovle(respones.data);
            })
            .catch((e) => {
              parent.sysDisplayMessage(
                "",
                -1,
                "",
                "",
                null,
                "取得資訊失敗:-101"
              );
              reject(e);
            });
        else
          axios
            .get("../json/tw/vehicle_license_tw.json")
            .then((respones) => {
              resovle(respones.data);
            })
            .catch((e) => {
              parent.sysDisplayMessage(
                "",
                -1,
                "",
                "",
                null,
                "取得資訊失敗:-101"
              );
              reject(e);
            });
      });
    },
    setVehicleInfo: function (data) {
      const self = this;
      self.whos_vehicle = data.whos_vehicle;
      self.vehicle_category = data.vehicle_category;
      self.approval_status = data.approval_status;
      self.upload_status = data.upload_status;
    },
    clearCompletedData: function () {
      const self = this;
      self.completedData = [];
      self.notCompletedData = [];
    },
    openImg: function (img) {
      let url;
      url = URL.createObjectURL(img);
      window.open(url);
    },
  },
};
</script>
<style scoped lang="scss">
.row-border {
  height: var(--test);
  border-bottom: 1px solid #d8d8d8;
  position: relative;
  &:hover {
    -webkit-box-shadow: 4px 4px 8px #5a5a5a;
    box-shadow: 4px 4px 8px #5a5a5a;
    background-color: #fff;
    color: #000 !important;
    border-left: 5px solid red;
    transition: 400ms;
    .process-state {
      font-weight: bold;
    }
  }
  .icon {
    color: grey;
    &:hover {
      color: black;
    }
  }
}
</style>