<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12" v-if="(user.submitted / user.surveys) >= 5 || user.surveys === 0">
                <div class="text-right">
                    <button class="btn btn-primary btn-sm" @click.prevent="showAddSurveyModal = true">+ Add survey</button>
                </div>

                <hr>
            </div>

            <div class="col-md-12" v-else>
                <div class="text-right">
                    <span class="mr-1">You need to complete {{ 5 - (user.submitted / user.surveys) }} more sureys to add another one.</span>
                    <button class="btn btn-primary btn-sm" disabled>+ Add survey</button>
                </div>

                <hr>
            </div>
        </div>

        <div class="row" style="margin-bottom: 15px">
            <div class="col-md-12">
                <h5>My IA Surveys</h5>
            </div>
        </div>
        <div class="row">
            <div v-if="surveys.loading" class="col-md-12 text-center">
                <spinner :status="surveys.loading" :depth="spinner.depth"></spinner>
            </div>
            <div class="col-md-4" v-for="(survey, index) in surveys.user" :key="'user' + index">
                <div class="panel panel-default">
                    <div class="panel-body row-eq-height">
                        <div class="col-md-12">
                            <h5 class="m-0">{{ survey.title }}</h5>
                            <ul class="list-inline m-0">
                                <li class="list-inline-item">{{ survey.subject | capitalize }}</li>
                                <li class="list-inline-item">added {{ survey.created_at | moment("from", "now") }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom: 15px">
            <div class="col-md-12">
                <h5>Community IA Surveys</h5>
            </div>
        </div>
        <div class="row">
            <div v-if="surveys.loading" class="col-md-12 text-center">
                <spinner :status="surveys.loading" :depth="spinner.depth"></spinner>
            </div>
            <div class="col-md-12" v-for="(survey, index) in surveys.community" :key="'community' + index">
                <div class="panel panel-default">
                    <div class="panel-body row-eq-height">
                        <div class="col-md-6">
                            <h5 class="m-0">{{ survey.title }}</h5>
                            <ul class="list-inline m-0">
                                <li class="list-inline-item">by {{ survey.user.name }}</li>
                                <li class="list-inline-item">{{ survey.subject | capitalize }}</li>
                                <li class="list-inline-item">added {{ survey.created_at | moment("from", "now") }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-right" v-if="survey.submitted === false">
                            <a :href="survey.link" target="_blank" @click="completeSurvey(survey.id)" class="btn btn-light btn-small align-middle">Complete survey</a>
                        </div>
                        <div class="col-md-6 text-right" v-else>
                            <a class="btn btn-light btn-small align-middle" disabled>Completed</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showAddSurveyModal">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">

                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add survey</h5>
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true" @click="showAddSurveyModal = false">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="alert alert-danger" v-if="form.errors.length > 0">
                                    <p><strong>Whoops!</strong> Something went wrong!</p>
                                    <br>
                                    <ul>
                                        <li v-for="(error, index) in form.errors" :key="index">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>
        
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Title</label>
                                        <input type="text" class="form-control" placeholder="e.g Smartphone usage amongst students" v-model="form.title">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Subject</label>
                                        <select class="form-control" v-model="form.subject">
                                            <option value="english">English</option>
                                            <option value="french">French</option>
                                            <option value="spanish">Spanish</option>

                                            <option value="globalpolitics">Global Politics</option>
                                            <option value="economics">Economics</option>
                                            <option value="psychology">Psychology</option>

                                            <option value="physics">Physics</option>
                                            <option value="chemisty">Chemisty</option>
                                            <option value="biology">Biology</option>
                                            <option value="ess">ESS</option>

                                            <option value="mathematics">Mathematics</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Secret key word</label>
                                        <input type="text" class="form-control" placeholder="e.g spooky" v-model="form.secret">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Link to online survey</label>
                                        <input type="text" class="form-control" placeholder="e.g link to Google Forms" v-model="form.link">
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="showAddSurveyModal = false">Close</button>
                                <button type="button" class="btn btn-primary" @click="storeSurvey" :disabled="form.loading"><spinner :status="form.loading" color="#FFFFFF" :size="spinner.size" :depth="spinner.depth"></spinner> Save</button>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
            </transition>
        </div>

        <div v-if="showActiveSurveyModal">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">

                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Complete Survey</h5>
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true" @click="showActiveSurveyModal = false">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="alert alert-danger" v-if="activeSurvey.errors.length > 0">
                                    <p><strong>Whoops!</strong> Something went wrong!</p>
                                    <br>
                                    <ul>
                                        <li v-for="(error, index) in activeSurvey.errors" :key="index">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>

                                <p>At the end of the survey, you should find a secret key word. Please write it below to confirm you have completed the survey.</p>
        
                                <div class="form-group">
                                    <label>Secret key word</label>
                                    <input type="text" class="form-control" placeholder="e.g spooky" v-model="activeSurvey.secret">
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="showActiveSurveyModal = false">Close</button>
                                <button type="button" class="btn btn-success" @click="storeSubmission" :disabled="activeSurvey.loading"><spinner :status="activeSurvey.loading" color="#FFFFFF" :size="spinner.size" :depth="spinner.depth"></spinner> Complete survey</button>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
    import Spinner from 'vue-spinner-component/src/Spinner.vue';

    export default {
        components: {
            Spinner
        },
        data() {
            return {
                surveys: {
                    user: [],
                    community: [],
                    loading: false
                },
                spinner: {
                    depth: 1,
                    size: 10
                },
                form: {
                    errors: [],
                    title: '',
                    link: '',
                    subject: '',
                    secret: '',
                    loading: false
                },
                activeSurvey: {
                    errors: [],
                    id: '',
                    secret: '',
                    loading: false
                },
                user: {
                    // submitted: window.user.submitted,
                    // surveys: window.user.surveys
                    submitted: window.user.submitted.length,
                    surveys: window.user.surveys.length
                },
                showActiveSurveyModal: false,
                showAddSurveyModal: false
            }
        },
        mounted() {
            this.getSurveys();
        },
        methods: {
            getSurveys() {
                this.surveys.loading = true;

                axios.get('/dashboard/surveys').then(response => {
                    this.surveys.user = response.data.filter(survey => survey.user.id === window.user.user.id);
                    this.surveys.community = response.data.filter(survey => survey.user.id !== window.user.user.id);
                    this.surveys.loading = false;
                });
            },
            storeSurvey() {
                this.form.errors = [];
                this.form.loading = true;

                axios.post('/dashboard/surveys', this.form).then(response => {
                    this.getSurveys();

                    this.form.title = '';
                    this.form.link = '';
                    this.form.subject = '';
                    this.form.errors = [];
                    this.form.loading = false;

                    this.showAddSurveyModal = false;

                    this.user.surveys++;
                }).catch(response => {
                    this.form.loading = false;

                    if (typeof response.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            completeSurvey(id) {
                this.activeSurvey.id = id;
                this.activeSurvey.key = '';
                this.activeSurvey.completed = false;

                this.showActiveSurveyModal = true;
            },
            storeSubmission() {
                this.activeSurvey.errors = [];
                this.activeSurvey.loading = true;

                axios.post('/dashboard/submissions', { survey_id: this.activeSurvey.id, secret: this.activeSurvey.secret }).then(response => {
                    this.getSurveys();

                    this.activeSurvey.id = '';
                    this.activeSurvey.secret = '';
                    this.activeSurvey.errors = [];
                    this.form.loading = false;

                    this.showActiveSurveyModal = false;
                }).catch(response => {
                    this.activeSurvey.loading = false;

                    if (typeof response.data === 'object') {
                        this.activeSurvey.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.activeSurvey.errors = ['Something went wrong. Please try again.'];
                    }
                });
            }
        }

    }
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-title {
    display: inline-block;
}

.row-eq-height {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  align-items: center;  
}
.m-0 {
    margin: 0 !important;
}

.mr-1 {
    margin-right: 10px !important;
}

.font-italic {
    font-style: italic;
}

.font-weight-bold {
    font-weight: bold;
}

.btn.btn-light:hover {
    color: #222222;
}

.sl-spinner {
    display: inline-block;
}
</style>
