<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Surveys</div>

                    <div class="panel-body">
                        <a href="#" class="btn btn-primary pull-right btn-sm" @click.prevent="addSurvey">Add a New Survey</a>
                        <br>
                        <br>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Link</th>
                                        <th>Subject</th>
                                        <th>Created by</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(survey, index) in surveys" :key="'survey' + index">
                                        <td>{{ survey.id }}</td>
                                        <td>{{ survey.title }}</td>
                                        <td><a :href="survey.link" target="_blank">Link</a></td>
                                        <td>{{ survey.subject }}</td>
                                        <td>{{ getUserName(survey.user_id) }}</td>
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-xs" @click.prevent="editSurvey(survey)">Update</button>
                                             /
                                            <button class="btn btn-danger btn-xs" @click.prevent="deleteSurvey(survey)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Item Form Modal -->
                <div class="modal fade" id="modal-create-survey" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                <h4 class="modal-title">
                                    {{ form.editMode ? 'Edit Survey' : 'Create New Survey' }}
                                </h4>
                            </div>

                            <div class="modal-body">
                                <!-- Form Errors -->
                                <div class="alert alert-danger" v-if="form.errors.length > 0">
                                    <p><strong>Whoops!</strong> Something went wrong!</p>
                                    <br>
                                    <ul>
                                        <li v-for="(error, index) in form.errors" :key="'error' + index">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>

                                <!-- Create Survey Form -->
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Title</label>

                                        <div class="col-md-7">
                                            <input id="create-survey-title" type="text" class="form-control" v-model="form.title">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Link</label>

                                        <div class="col-md-7">
                                            <input id="create-survey-link" type="text" class="form-control" v-model="form.link">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Subject</label>
                                        
                                        <div class="col-md-7">
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
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Secret keyword</label>

                                        <div class="col-md-7">
                                            <input type="text" class="form-control" v-model="form.secret">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">User</label>

                                        <div class="col-md-7">
                                            <select class="form-control" v-model="form.user_id">
                                                <option v-for="(user, index) in users" :value="user.id" :key="index">{{ user.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Modal Actions -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                <button v-if="form.editMode" type="button" class="btn btn-primary" @click.prevent="updateSurvey">
                                    Update
                                </button>
                                <button v-else type="button" class="btn btn-primary" @click.prevent="storeSurvey">
                                    Create
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>


<script>
    export default {
        data() {
            return {
                surveys: [],
                users: [],
                form: {
                    editMode: false,
                    errors: [],
                    title: '',
                    link: '',
                    subject: '',
                    secret: '',
                    user_id: ''
                }
            }
        },
        mounted() {
            this.getUsers();
            this.getSurveys();
        },
        methods: {
            getUserName(id) {
                return this.users.find(user => user.id === id).name;
            },
            getUsers() {
                axios.get('/admin/users').then(response => {
                    this.users = response.data;
                });
            },
            getSurveys() {
                axios.get('/admin/surveys').then(response => {
                    this.surveys = response.data;
                });
            },
            addSurvey(survey) {
                this.form.editMode = false;
                this.form.id = undefined;
                this.form.title = '';
                this.form.link = '';
                this.form.user_id = '';
                this.form.subject = '';
                this.form.secret = '';

                $('#modal-create-survey').modal('show');
            },
            editSurvey(survey) {
                this.form.editMode = true;
                this.form.id = survey.id;
                this.form.title = survey.title;
                this.form.link = survey.link;
                this.form.user_id = survey.user_id;
                this.form.subject = survey.subject;
                this.form.secret = '';

                $('#modal-create-survey').modal('show');
            },
            storeSurvey() {
                this.form.errors = [];

                axios.post('/admin/surveys', this.form).then(response => {
                    this.getSurveys();

                    this.form.title = '';
                    this.form.link = '';
                    this.form.user_id = '';
                    this.form.subject = '',
                    this.form.secret = '';
                    this.form.errors = [];

                    $('#modal-create-survey').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            updateSurvey() {
                this.form.errors = [];

                axios.put('/admin/surveys/' + this.form.id, this.form).then(response => {
                    this.getSurveys();

                    this.form.title = '';
                    this.form.link = '';
                    this.form.user_id = '';
                    this.form.subject = '';
                    this.form.errors = [];
                    this.form.secret = '';
                    this.form.editMode = false;

                    $('#modal-create-survey').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            deleteSurvey(survey) {
                axios.delete('/admin/surveys/' + survey.id).then(response => {
                    this.getSurveys();
                });
            }
        }
    }
</script>
